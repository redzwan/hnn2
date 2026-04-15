<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Vanilo\Order\Models\Order;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Sales';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Order Status')
                ->schema([
                    Forms\Components\Select::make('status')
                        ->options([
                            'pending'    => 'Pending',
                            'processing' => 'Processing',
                            'completed'  => 'Completed',
                            'cancelled'  => 'Cancelled',
                        ])
                        ->required(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->label('Order #')
                    ->searchable()
                    ->copyable()
                    ->weight('semibold'),
                Tables\Columns\TextColumn::make('billpayer.email')
                    ->label('Customer Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('billpayer.firstname')
                    ->label('First Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('billpayer.lastname')
                    ->label('Last Name')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'primary' => 'processing',
                        'success' => 'completed',
                        'danger'  => 'cancelled',
                    ]),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->getStateUsing(fn (Order $record) => '$ ' . number_format($record->total(), 2)),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('M j, Y g:i A')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending'    => 'Pending',
                        'processing' => 'Processing',
                        'completed'  => 'Completed',
                        'cancelled'  => 'Cancelled',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Order Information')
                ->schema([
                    Infolists\Components\TextEntry::make('number')->label('Order Number')->copyable(),
                    Infolists\Components\TextEntry::make('status')->badge(),
                    Infolists\Components\TextEntry::make('created_at')->label('Placed At')->dateTime(),
                ])->columns(3),

            Infolists\Components\Section::make('Customer Details')
                ->schema([
                    Infolists\Components\TextEntry::make('billpayer.firstname')->label('First Name'),
                    Infolists\Components\TextEntry::make('billpayer.lastname')->label('Last Name'),
                    Infolists\Components\TextEntry::make('billpayer.email')->label('Email'),
                    Infolists\Components\TextEntry::make('billpayer.phone')->label('Phone'),
                ])->columns(2),

            Infolists\Components\Section::make('Shipping Address')
                ->schema([
                    Infolists\Components\TextEntry::make('billpayer.address.address')->label('Street'),
                    Infolists\Components\TextEntry::make('billpayer.address.city')->label('City'),
                    Infolists\Components\TextEntry::make('billpayer.address.postalcode')->label('Postcode'),
                ])->columns(3),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListOrders::route('/'),
            'view'   => Pages\ViewOrder::route('/{record}'),
            'edit'   => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
