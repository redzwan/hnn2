<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Vanilo\Order\Models\Order;
use Vanilo\Product\Models\Product;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $totalRevenue = Order::where('status', 'completed')
            ->get()
            ->sum(fn ($order) => $order->total());

        return [
            Stat::make('Total Products', Product::count())
                ->description(Product::actives()->count().' active')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),

            Stat::make('Total Orders', Order::count())
                ->description(Order::where('status', 'pending')->count().' pending')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('warning'),

            Stat::make('Revenue', '$ '.number_format($totalRevenue, 2))
                ->description('From completed orders')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}
