<?php

namespace App\Filament\Widgets;

use App\Models\OrderItem;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class SalesChart extends ChartWidget
{
    protected static ?string $heading = 'Sales (Last 30 Days)';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = [];
        $labels = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $labels[] = Carbon::now()->subDays($i)->format('M d');

            $total = DB::table('order_items')
                ->whereDate('created_at', $date)
                ->sum('price');

            $data[] = $total / 100; // Convert to $
        }

        return [
            'datasets' => [
                [
                    'label' => 'Sales ($)',
                    'data' => $data,
                    'fill' => true,
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
