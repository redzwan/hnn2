<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Catalogue';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Product Details')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))
                        ),
                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(Product::class, 'slug', ignoreRecord: true),
                    Forms\Components\TextInput::make('sku')
                        ->label('SKU')
                        ->required()
                        ->maxLength(100)
                        ->unique(Product::class, 'sku', ignoreRecord: true),
                    Forms\Components\TextInput::make('price')
                        ->numeric()
                        ->prefix('RM')
                        ->required()
                        ->minValue(0),
                ])->columns(2),

            Forms\Components\Section::make('Categories')
                ->schema([
                    Forms\Components\Select::make('categories')
                        ->label('Categories')
                        ->multiple()
                        ->relationship('categories', 'name')
                        ->preload(),
                ]),

            Forms\Components\Section::make('Images')
                ->schema([
                    SpatieMediaLibraryFileUpload::make('images')
                        ->collection('images')
                        ->multiple()
                        ->reorderable()
                        ->image()
                        ->imageEditor()
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Description')
                ->schema([
                    Forms\Components\RichEditor::make('description')
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Status')
                ->schema([
                    Forms\Components\Select::make('state')
                        ->options([
                            'draft' => 'Draft',
                            'active' => 'Active',
                            'inactive' => 'Inactive',
                            'retired' => 'Retired',
                        ])
                        ->default('active')
                        ->required(),
                ])->columns(1),

            Forms\Components\Section::make('SEO')
                ->description('Override search engine title and description for this product.')
                ->icon('heroicon-o-magnifying-glass')
                ->schema([
                    Forms\Components\TextInput::make('seo_title')
                        ->label('SEO Title')
                        ->placeholder('Leave blank to use product name')
                        ->maxLength(70)
                        ->helperText('Max 70 characters. Shown in Google search results.'),
                    Forms\Components\Textarea::make('seo_description')
                        ->label('SEO Description')
                        ->placeholder('Leave blank to use product description')
                        ->maxLength(160)
                        ->rows(2)
                        ->helperText('Max 160 characters. Shown below the title in search results.'),
                    Forms\Components\Toggle::make('noindex')
                        ->label('Hide from search engines (noindex)')
                        ->helperText('Enable to exclude this product from Google and sitemap.')
                        ->default(false),
                ])->columns(1)->collapsible(),

            Forms\Components\Section::make('Stock')
                ->schema([
                    Forms\Components\Toggle::make('is_stockable')
                        ->label('Track Stock')
                        ->helperText('Enable to limit sales based on available stock quantity.')
                        ->live()
                        ->default(false),
                    Forms\Components\TextInput::make('stock')
                        ->label('Stock Available')
                        ->numeric()
                        ->minValue(0)
                        ->default(0)
                        ->suffix('units')
                        ->visible(fn (Forms\Get $get): bool => (bool) $get('is_stockable')),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('images')
                    ->collection('images')
                    ->circular(false)
                    ->width(60)
                    ->height(60),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->copyable()
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('price')
                    ->money('myr')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('state')
                    ->colors([
                        'success' => 'active',
                        'warning' => 'draft',
                        'danger' => fn ($state) => in_array($state, ['inactive', 'retired']),
                    ]),
                Tables\Columns\TextColumn::make('stock')
                    ->label('Stock')
                    ->formatStateUsing(fn ($record) => $record->is_stockable ? number_format($record->stock) : '∞')
                    ->badge()
                    ->color(fn ($record) => match (true) {
                        ! $record->is_stockable => 'gray',
                        $record->stock <= 0 => 'danger',
                        $record->stock <= 5 => 'warning',
                        default => 'success',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('state')
                    ->options([
                        'draft' => 'Draft',
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'retired' => 'Retired',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
