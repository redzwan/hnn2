<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 p-6">
    @foreach($products as $product)
        <div class="group flex flex-col bg-white border border-gray-200 shadow-sm rounded-xl overflow-hidden hover:shadow-md transition">
            <a href="/products/{{ $product->slug }}" class="block overflow-hidden h-48 bg-gradient-to-br from-blue-50 to-indigo-100">
                @if($product->hasMedia('images'))
                    <img src="{{ $product->getFirstMediaUrl('images') }}"
                         alt="{{ $product->name }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="size-16 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif
            </a>
            <div class="p-4 md:p-5 flex flex-col flex-1">
                <a href="/products/{{ $product->slug }}">
                    <h3 class="text-lg font-bold text-gray-800 hover:text-blue-600 transition-colors">{{ $product->name }}</h3>
                </a>
                <p class="mt-2 text-gray-500 text-sm">SKU: {{ $product->sku }}</p>
                <p class="mt-3 text-xl font-semibold text-blue-600">RM {{ number_format($product->price, 2) }}</p>

                <button wire:click="addToCart({{ $product->id }})"
                        class="mt-auto pt-4 w-full py-2 px-3 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-black text-white hover:bg-gray-800 disabled:opacity-50">
                    Add to Cart
                </button>
            </div>
        </div>
    @endforeach
</div>
