{{-- Sales Reports Page View --}}
<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Filters --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Date Range</h2>
            <div class="flex flex-wrap gap-2">
                <button
                    wire:click="$set('period', '7days')"
                    class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ $period === '7days' ? 'bg-amber-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    7 Days
                </button>
                <button
                    wire:click="$set('period', '30days')"
                    class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ $period === '30days' ? 'bg-amber-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    30 Days
                </button>
                <button
                    wire:click="$set('period', '90days')"
                    class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ $period === '90days' ? 'bg-amber-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    90 Days
                </button>
                <button
                    wire:click="$set('period', 'year')"
                    class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ $period === 'year' ? 'bg-amber-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Year
                </button>
            </div>
        </div>

        {{-- Metrics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Sales</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">RM {{ number_format($totalSales, 2) }}</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-lg">
                        <x-heroicon-o-currency-dollar class="w-8 h-8 text-green-600" />
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Orders</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalOrders) }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <x-heroicon-o-shopping-bag class="w-8 h-8 text-blue-600" />
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Avg Order Value</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">RM {{ number_format($averageOrderValue, 2) }}</p>
                    </div>
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <x-heroicon-o-chart-bar class="w-8 h-8 text-purple-600" />
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Customers</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalCustomers) }}</p>
                    </div>
                    <div class="p-3 bg-amber-100 rounded-lg">
                        <x-heroicon-o-users class="w-8 h-8 text-amber-600" />
                    </div>
                </div>
            </div>
        </div>

        {{-- Top Products --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Top Selling Products</h2>
            @if(count($topProducts) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left text-sm text-gray-500 border-b">
                                <th class="pb-3 font-medium">Product</th>
                                <th class="pb-3 font-medium text-right">Quantity Sold</th>
                                <th class="pb-3 font-medium text-right">Total Sales</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($topProducts as $product)
                                <tr class="text-sm">
                                    <td class="py-3 text-gray-900">{{ $product->name ?? 'Product #' . $product->product_id }}</td>
                                    <td class="py-3 text-gray-600 text-right">{{ number_format($product->total_qty) }}</td>
                                    <td class="py-3 text-gray-900 font-medium text-right">RM {{ number_format($product->total_sales, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-center py-8">No sales data for this period.</p>
            @endif
        </div>

        {{-- Sales by Day --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Daily Sales Trend</h2>
            @if(count($salesByDay) > 0)
                <div class="space-y-2 max-h-64 overflow-y-auto">
                    @foreach($salesByDay as $day)
                        <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                            <span class="text-sm text-gray-600">{{ $day['date'] }}</span>
                            <span class="text-sm font-medium text-gray-900">RM {{ number_format($day['total'], 2) }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-8">No sales data for this period.</p>
            @endif
        </div>

        {{-- Sales by Status --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Orders by Status</h2>
            @if(count($salesByStatus) > 0)
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($salesByStatus as $status)
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($status->count) }}</p>
                            <p class="text-sm text-gray-500 mt-1">{{ ucfirst($status->status) }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-8">No orders for this period.</p>
            @endif
        </div>
    </div>
</x-filament-panels::page>
