<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShippingMethodResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Vanilo\Shipment\Models\Carrier;
use Vanilo\Shipment\Models\ShippingMethod;

class ShippingMethodResource extends Resource
{
    protected static ?string $model = ShippingMethod::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationGroup = 'Shipping';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Shipping Methods';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Method Details')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Select::make('carrier_id')
                        ->label('Carrier')
                        ->options(Carrier::actives()->pluck('name', 'id'))
                        ->required()
                        ->searchable(),
                    Forms\Components\Toggle::make('is_active')
                        ->label('Active')
                        ->default(true),
                ])->columns(2),

            Forms\Components\Section::make('Pricing')
                ->schema([
                    Forms\Components\TextInput::make('configuration.cost')
                        ->label('Shipping Fee (RM)')
                        ->numeric()
                        ->prefix('RM')
                        ->required()
                        ->minValue(0),
                    Forms\Components\TextInput::make('configuration.free_threshold')
                        ->label('Free Shipping Above (RM)')
                        ->numeric()
                        ->prefix('RM')
                        ->placeholder('Leave blank to disable')
                        ->helperText('Orders above this amount get free shipping'),
                    Forms\Components\TextInput::make('configuration.title')
                        ->label('Fee Label')
                        ->placeholder('Shipping fee')
                        ->maxLength(255),
                ])->columns(2),

            Forms\Components\Section::make('Delivery Estimate')
                ->schema([
                    Forms\Components\TextInput::make('eta_min')
                        ->label('Min Days')
                        ->numeric()
                        ->minValue(1),
                    Forms\Components\TextInput::make('eta_max')
                        ->label('Max Days')
                        ->numeric()
                        ->minValue(1),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('carrier_name')
                    ->label('Carrier')
                    ->badge()
                    ->color('info')
                    ->getStateUsing(fn ($record) => $record->carrier?->name ?? '—'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),
                Tables\Columns\TextColumn::make('shipping_fee')
                    ->label('Fee')
                    ->getStateUsing(fn ($record) => 'RM '.number_format((float) ($record->configuration['cost'] ?? 0), 2)),
                Tables\Columns\TextColumn::make('free_above')
                    ->label('Free Above')
                    ->getStateUsing(fn ($record) => isset($record->configuration['free_threshold']) && $record->configuration['free_threshold']
                        ? 'RM '.number_format((float) $record->configuration['free_threshold'], 2)
                        : '—'),
                Tables\Columns\TextColumn::make('eta')
                    ->label('ETA')
                    ->getStateUsing(fn ($record) => $record->eta_min && $record->eta_max
                        ? "{$record->eta_min}–{$record->eta_max} days"
                        : '—'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
                Tables\Filters\SelectFilter::make('carrier_id')
                    ->label('Carrier')
                    ->options(Carrier::pluck('name', 'id')),
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
            'index' => Pages\ListShippingMethods::route('/'),
            'create' => Pages\CreateShippingMethod::route('/create'),
            'edit' => Pages\EditShippingMethod::route('/{record}/edit'),
        ];
    }
}
