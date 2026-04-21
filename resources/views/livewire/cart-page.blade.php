<div>
    @if($isEmpty)
        <!-- Empty State -->
        <div class="text-center py-24">
            <div class="inline-flex items-center justify-center size-20 bg-gray-100 rounded-full mb-6">
                <svg class="size-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Your cart is empty</h2>
            <p class="text-gray-500 mb-8">Looks like you haven't added anything yet.</p>
            <a href="/products" class="inline-flex items-center gap-2 py-3 px-6 font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
                </svg>
                Browse Products
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Cart Items -->
            <div class="lg:col-span-2 space-y-4">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-semibold text-gray-800">{{ $items->count() }} {{ Str::plural('item', $items->count()) }}</h2>
                    <button wire:click="clearCart" wire:confirm="Clear all items from cart?"
                            class="text-sm text-red-500 hover:text-red-700 hover:underline transition-colors">
                        Clear all
                    </button>
                </div>

                @foreach($items as $item)
                    <div class="flex gap-4 bg-white border border-gray-100 rounded-2xl p-4 shadow-sm">
                        <!-- Product image -->
                        <div class="shrink-0 w-20 h-20 bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl overflow-hidden flex items-center justify-center">
                            @if($item->product && $item->product->hasMedia('images'))
                                <img src="{{ $item->product->getFirstMediaUrl('images') }}"
                                     alt="{{ $item->product->name }}"
                                     class="w-full h-full object-cover">
                            @else
                                <svg class="size-8 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            @endif
                        <!-- Product image placeholder -->
                        <div class="shrink-0 w-20 h-20 bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl flex items-center justify-center">
                            <svg class="size-8 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <h3 class="font-semibold text-gray-900 truncate">{{ $item->product->name }}</h3>
                                    <p class="text-xs text-gray-400 mt-0.5">SKU: {{ $item->product->sku }}</p>
                                </div>
                                <button wire:click="removeItem({{ $item->id }})"
                                        class="shrink-0 text-gray-400 hover:text-red-500 transition-colors p-1">
                                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="flex items-center justify-between mt-3">
                                <div class="flex items-center gap-2">
                                    <button wire:click="updateQty({{ $item->id }}, {{ $item->quantity - 1 }})"
                                            class="size-7 flex items-center justify-center border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors text-sm font-bold">
                                        −
                                    </button>
                                    <span class="w-8 text-center text-sm font-semibold">{{ $item->quantity }}</span>
                                    <button wire:click="updateQty({{ $item->id }}, {{ $item->quantity + 1 }})"
                                            class="size-7 flex items-center justify-center border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors text-sm font-bold">
                                        +
                                    </button>
                                </div>
                                <span class="font-bold text-gray-900">${{ number_format($item->total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm sticky top-24">
                    <h2 class="text-lg font-bold text-gray-900 mb-6">Order Summary</h2>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal</span>
                            <span class="font-medium">${{ number_format($total, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Shipping</span>
                            <span class="font-medium text-green-600">Free</span>
                        </div>
                        <div class="border-t border-gray-100 pt-3 flex justify-between text-base font-bold text-gray-900">
                            <span>Total</span>
                            <span>${{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    @auth
                        <a href="/checkout"
                           class="mt-6 w-full py-3.5 px-4 inline-flex justify-center items-center gap-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors">
                            Proceed to Checkout
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                    @else
                        <a href="/login"
                           class="mt-6 w-full py-3.5 px-4 inline-flex justify-center items-center gap-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors">
                            Login to Checkout
                        </a>
                    @endauth

                    <a href="/products" class="mt-3 w-full py-2.5 px-4 inline-flex justify-center items-center text-sm text-gray-500 hover:text-gray-700 transition-colors">
                        ← Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
