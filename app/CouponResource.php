<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CouponResource\Pages;
use App\Models\Coupon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationLabel = 'Coupons';

    protected static ?string $modelLabel = 'Coupon';

    protected static ?string $pluralModelLabel = 'Coupons';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Coupon Details')
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('Coupon Code')
                            ->required()
                            ->unique(Coupon::class, 'code', ignorable: fn ($record) => $record)
                            ->maxLength(50),
                        Forms\Components\Select::make('promotion_id')
                            ->relationship('promotion', 'name')
                            ->label('Promotion'),
                        Forms\Components\DateTimePicker::make('expires_at')
                            ->label('Expires At'),
                    ]),
                Forms\Components\Section::make('Usage Limits')
                    ->schema([
                        Forms\Components\TextInput::make('usage_limit')
                            ->label('Usage Limit')
                            ->numeric()
                            ->nullable()
                            ->helperText('Maximum total uses (leave empty for unlimited)'),
                        Forms\Components\TextInput::make('per_customer_usage_limit')
                            ->label('Per Customer Limit')
                            ->numeric()
                            ->nullable()
                            ->helperText('Maximum uses per customer'),
                        Forms\Components\TextInput::make('usage_count')
                            ->label('Current Usage')
                            ->numeric()
                            ->disabled(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('promotion.name')
                    ->label('Promotion')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('usage_count')
                    ->label('Used')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('usage_limit')
                    ->label('Limit')
                    ->numeric()
                    ->sortable()
                    ->toggleable()
                    ->placeholder('∞'),
                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Expires')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('expires_at')
                    ->label('Status')
                    ->boolean()
                    ->getStateUsing(fn ($record) => ! $record->isExpired() && (! $record->usage_limit || $record->usage_count < $record->usage_limit))
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}
