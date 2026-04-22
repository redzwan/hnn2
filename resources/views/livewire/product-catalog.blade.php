<div>
    <!-- Filters Bar -->
    <div class="flex flex-col sm:flex-row gap-3 mb-8">
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="size-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="search"
                   type="text"
                   placeholder="Search products..."
                   class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
        </div>
        <select wire:model.live="sortBy"
                class="py-2.5 px-4 text-sm border border-gray-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
            <option value="latest">Newest first</option>
            <option value="price_asc">Price: Low to High</option>
            <option value="price_desc">Price: High to Low</option>
            <option value="name">Name A–Z</option>
        </select>
    </div>

    <!-- Loading indicator -->
    <div wire:loading.delay class="text-center py-4">
        <div class="inline-flex items-center gap-2 text-sm text-gray-500">
            <svg class="animate-spin size-4 text-blue-600" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
            </svg>
            Searching...
        </div>
    </div>

    <!-- Products Grid -->
    @if($products->isEmpty())
        <div class="text-center py-20">
            <svg class="mx-auto size-14 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
            <h3 class="mt-4 text-lg font-semibold text-gray-800">No products found</h3>
            <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filters.</p>
            @if($search)
                <button wire:click="$set('search', '')" class="mt-4 text-sm text-blue-600 hover:underline">Clear search</button>
            @endif
        </div>
    @else
        <div wire:loading.class="opacity-50" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 transition-opacity">
            @foreach($products as $product)
                <div class="group flex flex-col bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-200">
                    <!-- Product Image -->
                    <a href="/products/{{ $product->slug }}" class="block overflow-hidden bg-gradient-to-br from-blue-50 to-indigo-100 h-48 flex items-center justify-center">
                        @if($product->hasMedia('images'))
                            <img src="{{ $product->getFirstMediaUrl('images') }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <svg class="size-16 text-blue-200 group-hover:text-blue-300 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        @endif
                    </a>

                    <!-- Product Info -->
                    <div class="flex flex-col flex-1 p-4">
                        <div class="flex-1">
                            <p class="text-xs text-gray-400 font-medium mb-1">SKU: {{ $product->sku }}</p>
                            <a href="/products/{{ $product->slug }}" class="block">
                                <h3 class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors line-clamp-2 leading-snug">
                                    {{ $product->name }}
                                </h3>
                            </a>
                        </div>
                        <div class="mt-3 flex items-center justify-between">
                            <span class="text-lg font-bold text-gray-900">$ {{ number_format($product->price, 2) }}</span>
                        </div>
                        <button wire:click="addToCart({{ $product->id }})"
                                wire:loading.attr="disabled"
                                wire:target="addToCart({{ $product->id }})"
                                class="mt-3 w-full py-2.5 px-4 inline-flex justify-center items-center gap-2 text-sm font-semibold rounded-xl transition-all duration-200
                                    {{ $addedProductId === $product->id
                                        ? 'bg-green-500 text-white'
                                        : 'bg-gray-900 text-white hover:bg-blue-600' }}">
                            @if($addedProductId === $product->id)
                                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Added!
                            @else
                                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                Add to Cart
                            @endif
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-10">
            {{ $products->links() }}
        </div>
    @endif
</div>
