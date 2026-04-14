<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use App\Models\Category;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ViewRecord;
use Filament\Tables;
use Filament\Tables\Table;

class ViewCategoryProducts extends ViewRecord
{
    protected static string $resource = CategoryResource::class;

    protected static ?string $title = 'Products';

    public function getTitle(): string
    {
        return $this->record->name.' - Products';
    }

    public function getTabs(): array
    {
        return [
            'info' => Tab::make('Info')
                ->icon('heroicon-o-information-circle'),
            'products' => Tab::make('Products')
                ->icon('heroicon-o-shopping-bag')
                ->badge(fn () => $this->record->products()->count()),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->record(fn () => $this->record)
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('images')
                    ->collection('images')
                    ->width(60)
                    ->height(60),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
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
                Tables\Actions\EditAction::make()
                    ->url(fn (Category $record) => route('filament.admin.resources.products.edit', ['record' => $record])),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
