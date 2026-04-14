<?php

namespace App\Filament\Pages;

use App\Models\Order;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesReports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Sales Reports';

    protected static ?string $title = 'Sales Analytics';

    protected static ?string $navigationGroup = 'Reports';

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'reports/sales';

    protected static bool $shouldRegisterNavigation = true;

    protected static string $view = 'filament.pages.sales-reports';

    public $startDate;
    public $endDate;
    public $period = '30days';

    // Metrics
    public $totalSales = 0;
    public $totalOrders = 0;
    public $averageOrderValue = 0;
    public $totalCustomers = 0;

    // Chart data
    public $salesByDay = [];
    public $topProducts = [];
    public $salesByStatus = [];

    public function mount(): void
    {
        $this->setDateRange();
        $this->loadMetrics();
    }

    public function setDateRange(): void
    {
        $this->startDate = match ($this->period) {
            '7days' => Carbon::now()->subDays(7)->format('Y-m-d'),
            '30days' => Carbon::now()->subDays(30)->format('Y-m-d'),
            '90days' => Carbon::now()->subDays(90)->format('Y-m-d'),
            'year' => Carbon::now()->subYear()->format('Y-m-d'),
            'custom' => $this->startDate ?? Carbon::now()->subDays(30)->format('Y-m-d'),
            default => Carbon::now()->subDays(30)->format('Y-m-d'),
        };

        $this->endDate = Carbon::now()->format('Y-m-d');
    }

    public function loadMetrics(): void
    {
        $start = Carbon::parse($this->startDate)->startOfDay();
        $end = Carbon::parse($this->endDate)->endOfDay();

        // Total sales (sum of order items price * quantity)
        $this->totalSales = DB::table('order_items')
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('SUM(price * quantity) as total')
            ->value('total') ?? 0;

        // Total orders
        $this->totalOrders = DB::table('orders')
            ->whereBetween('created_at', [$start, $end])
            ->count();

        // Average order value
        $this->averageOrderValue = $this->totalOrders > 0 
            ? $this->totalSales / $this->totalOrders 
            : 0;

        // Total customers with orders
        $this->totalCustomers = DB::table('orders')
            ->whereBetween('created_at', [$start, $end])
            ->distinct('customer_id')
            ->count('customer_id');

        // Sales by day
        $this->salesByDay = DB::table('order_items')
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as date, SUM(price * quantity) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(fn ($item) => [
                'date' => Carbon::parse($item->date)->format('M d'),
                'total' => round($item->total, 2),
            ])
            ->toArray();

        // Top selling products
        $this->topProducts = DB::table('order_items')
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('product_id, name, SUM(quantity) as total_qty, SUM(price * quantity) as total_sales')
            ->groupBy('product_id', 'name')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get()
            ->toArray();

        // Sales by status
        $this->salesByStatus = DB::table('orders')
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('status, COUNT(*) as count, SUM(1) as total')
            ->groupBy('status')
            ->get()
            ->toArray();
    }

    public function updatedPeriod(): void
    {
        $this->setDateRange();
        $this->loadMetrics();
    }

    public function updatedStartDate(): void
    {
        if ($this->startDate && $this->endDate) {
            $this->period = 'custom';
            $this->loadMetrics();
        }
    }

    public function updatedEndDate(): void
    {
        if ($this->startDate && $this->endDate) {
            $this->period = 'custom';
            $this->loadMetrics();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('refresh')
                ->label('Refresh')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    $this->loadMetrics();
                    $this->notify('success', 'Data refreshed successfully.');
                }),
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('period')
                ->label('Period')
                ->options([
                    '7days' => 'Last 7 Days',
                    '30days' => 'Last 30 Days',
                    '90days' => 'Last 90 Days',
                    'year' => 'Last Year',
                    'custom' => 'Custom Range',
                ])
                ->afterStateUpdated(fn () => $this->updatedPeriod()),
            DatePicker::make('startDate')
                ->label('Start Date')
                ->visible(fn () => $this->period === 'custom')
                ->afterStateUpdated(fn () => $this->updatedStartDate()),
            DatePicker::make('endDate')
                ->label('End Date')
                ->visible(fn () => $this->period === 'custom')
                ->afterStateUpdated(fn () => $this->updatedEndDate()),
        ];
    }
}
