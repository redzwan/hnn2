<div>
    {{-- Category filter pills --}}
    @if($categories->isNotEmpty())
        <div class="flex items-center gap-2 flex-wrap mb-6">
            <span class="text-xs text-stone-400 tracking-wide mr-1">Filter:</span>
            <button wire:click="filterByCategory(null)"
                    class="px-3 py-1.5 text-xs font-medium rounded-full border transition-colors duration-150
                        {{ is_null($categoryId) ? 'border-primary bg-primary text-white' : 'border-gray-200 text-gray-600 hover:border-primary-light hover:text-primary' }}">
                All
            </button>
            @foreach($categories as $category)
                <button wire:click="filterByCategory({{ $category->id }})"
                        class="px-3 py-1.5 text-xs font-medium rounded-full border transition-colors duration-150
                            {{ $categoryId === $category->id ? 'border-primary bg-primary text-white' : 'border-gray-200 text-gray-600 hover:border-primary-light hover:text-primary' }}">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>
    @endif

    {{-- Sort + search + count --}}
    <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-8 pb-6 border-b border-gray-100">
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="size-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="search"
                   type="text"
                   placeholder="Search products…"
                   class="w-full pl-10 pr-4 py-2 text-sm border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-primary transition-colors">
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <select wire:model.live="sortBy"
                    class="py-2 px-3 text-sm border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-primary transition-colors cursor-pointer">
                <option value="latest">Newest first</option>
                <option value="price_asc">Price: Low to High</option>
                <option value="price_desc">Price: High to Low</option>
                <option value="name">Name A–Z</option>
            </select>
            <span wire:loading.remove class="text-sm text-gray-400 whitespace-nowrap">
                {{ $products->total() }} {{ Str::plural('product', $products->total()) }}
            </span>
        </div>
    </div>

    {{-- Loading --}}
    <div wire:loading.delay class="text-center py-8">
        <svg class="animate-spin size-5 text-blue-400 mx-auto" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
        </svg>
    </div>

    {{-- Empty state --}}
    @if($products->isEmpty())
        <div class="text-center py-20 bg-gray-50 rounded-2xl">
            <p class="text-gray-500 text-lg mb-4">No products found.</p>
            @if($search || $categoryId)
                <button wire:click="$set('search', ''); filterByCategory(null)"
                        class="text-sm text-primary hover:underline">
                    Clear filters
                </button>
            @endif
        </div>

    {{-- Grid --}}
    @else
        <div wire:loading.class="opacity-50"
             class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-6 transition-opacity duration-200">
            @foreach($products as $product)
                @include('components.theme.product-card-editorial', ['product' => $product])
            @endforeach
        </div>

        <div class="mt-12">
            {{ $products->links() }}
        </div>
    @endif
</div>
