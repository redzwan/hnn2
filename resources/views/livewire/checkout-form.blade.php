<div>
    @if($orderPlaced)
        <!-- Success State -->
        <div class="max-w-lg mx-auto text-center py-20">
            <div class="inline-flex items-center justify-center size-20 bg-green-100 rounded-full mb-6">
                <svg class="size-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-3">Order Placed!</h2>
            <p class="text-gray-500 text-lg mb-2">Thank you for your purchase.</p>
            <p class="text-sm text-gray-400 mb-8">Order number: <span class="font-mono font-semibold text-gray-700">{{ $orderNumber }}</span></p>
            <a href="/products"
               class="inline-flex items-center gap-2 py-3 px-8 font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors">
                Continue Shopping
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-10">
            <!-- Form -->
            <div class="lg:col-span-3">
                <form wire:submit="placeOrder" class="space-y-6">

                    <!-- Step 1: Customer Info -->
                    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
                        <h2 class="text-base font-bold text-gray-900 mb-5 flex items-center gap-2">
                            <span class="size-6 inline-flex items-center justify-center bg-blue-600 text-white text-xs font-bold rounded-full">1</span>
                            Customer Information
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">First Name *</label>
                                <input wire:model="firstname" type="text" placeholder="John"
                                       class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 @error('firstname') border-red-400 @enderror">
                                @error('firstname') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Last Name *</label>
                                <input wire:model="lastname" type="text" placeholder="Doe"
                                       class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 @error('lastname') border-red-400 @enderror">
                                @error('lastname') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Email *</label>
                                <input wire:model="email" type="email" placeholder="john@example.com"
                                       class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-400 @enderror">
                                @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Phone *</label>
                                <input wire:model="phone" type="tel" placeholder="+60 12-345 6789"
                                       class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone') border-red-400 @enderror">
                                @error('phone') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Shipping Address -->
                    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
                        <h2 class="text-base font-bold text-gray-900 mb-5 flex items-center gap-2">
                            <span class="size-6 inline-flex items-center justify-center bg-blue-600 text-white text-xs font-bold rounded-full">2</span>
                            Shipping Address
                        </h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Street Address *</label>
                                <input wire:model="address" type="text" placeholder="123 Jalan Bukit Bintang"
                                       class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 @error('address') border-red-400 @enderror">
                                @error('address') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">City *</label>
                                    <input wire:model="city" type="text" placeholder="Kuala Lumpur"
                                           class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 @error('city') border-red-400 @enderror">
                                    @error('city') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">State *</label>
                                    <select wire:model="state"
                                            class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white @error('state') border-red-400 @enderror">
                                        <option value="">Select State</option>
                                        <option value="Johor">Johor</option>
                                        <option value="Kedah">Kedah</option>
                                        <option value="Kelantan">Kelantan</option>
                                        <option value="Melaka">Melaka</option>
                                        <option value="Negeri Sembilan">Negeri Sembilan</option>
                                        <option value="Pahang">Pahang</option>
                                        <option value="Perak">Perak</option>
                                        <option value="Perlis">Perlis</option>
                                        <option value="Pulau Pinang">Pulau Pinang</option>
                                        <option value="Sabah">Sabah</option>
                                        <option value="Sarawak">Sarawak</option>
                                        <option value="Selangor">Selangor</option>
                                        <option value="Terengganu">Terengganu</option>
                                        <option value="W.P. Kuala Lumpur">W.P. Kuala Lumpur</option>
                                        <option value="W.P. Putrajaya">W.P. Putrajaya</option>
                                        <option value="W.P. Labuan">W.P. Labuan</option>
                                    </select>
                                    @error('state') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Postcode *</label>
                                    <input wire:model="zip" type="text" placeholder="50450"
                                           class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 @error('zip') border-red-400 @enderror">
                                    @error('zip') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            @auth
                            <div class="flex items-center gap-2">
                                <input wire:model="saveAsDefault" type="checkbox" id="save-default"
                                       class="size-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <label for="save-default" class="text-sm text-gray-600">Save as my default delivery address</label>
                            </div>
                            @endauth
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Order Notes <span class="text-gray-400">(optional)</span></label>
                                <textarea wire:model="notes" rows="3" placeholder="Any special instructions..."
                                          class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Shipping Method -->
                    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
                        <h2 class="text-base font-bold text-gray-900 mb-5 flex items-center gap-2">
                            <span class="size-6 inline-flex items-center justify-center bg-blue-600 text-white text-xs font-bold rounded-full">3</span>
                            Shipping Method
                        </h2>

                        @error('shippingMethodId')
                            <p class="mb-3 text-xs text-red-500">{{ $message }}</p>
                        @enderror

                        @if($shippingMethods->isEmpty())
                            <p class="text-sm text-gray-400">No shipping methods available. Please contact us.</p>
                        @else
                            <div class="space-y-3">
                                @foreach($shippingMethods->groupBy('carrier.name') as $carrierName => $methods)
                                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider pt-1">{{ $carrierName }}</p>
                                    @foreach($methods as $method)
                                        @php
                                            $cost = (float) ($method->configuration['cost'] ?? 0);
                                            $threshold = isset($method->configuration['free_threshold']) ? (float) $method->configuration['free_threshold'] : null;
                                            $isFree = $threshold !== null && $subtotal >= $threshold;
                                            $eta = ($method->eta_min && $method->eta_max) ? "{$method->eta_min}–{$method->eta_max} days" : null;
                                        @endphp
                                        <label class="flex items-start gap-4 p-4 border-2 rounded-xl cursor-pointer transition-all
                                            {{ $shippingMethodId == $method->id ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300' }}">
                                            <input wire:model.live="shippingMethodId" type="radio" name="shippingMethodId"
                                                   value="{{ $method->id }}" class="mt-0.5 text-blue-600">
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center justify-between gap-2 flex-wrap">
                                                    <span class="text-sm font-semibold text-gray-800">{{ $method->name }}</span>
                                                    <span class="text-sm font-bold {{ $isFree ? 'text-green-600' : 'text-gray-900' }}">
                                                        {{ $isFree ? 'FREE' : '$ ' . number_format($cost, 2) }}
                                                    </span>
                                                </div>
                                                @if($eta || $threshold)
                                                    <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-gray-500">
                                                        @if($eta)
                                                            <span class="flex items-center gap-1">
                                                                <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                </svg>
                                                                {{ $eta }}
                                                            </span>
                                                        @endif
                                                        @if($threshold && !$isFree)
                                                            <span class="text-green-600 font-medium">Free over $ {{ number_format($threshold, 2) }}</span>
                                                        @endif
                                                        @if($isFree)
                                                            <span class="text-green-600 font-medium">Free shipping applied!</span>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </label>
                                    @endforeach
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Step 4: Payment Method -->
                    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden" x-data>
                        <div class="px-6 pt-6 pb-4 flex items-center gap-2">
                            <span class="size-6 inline-flex items-center justify-center bg-blue-600 text-white text-xs font-bold rounded-full">4</span>
                            <h2 class="text-base font-bold text-gray-900">Payment Method</h2>
                        </div>

                        @error('paymentMethod')
                            <p class="px-6 pb-3 text-xs text-red-500">{{ $message }}</p>
                        @enderror

                        <div class="border-t border-gray-100 divide-y divide-gray-100">

                            {{-- ── BillPlz ── --}}
                            <div>
                                <button type="button"
                                        wire:click="$set('paymentMethod', 'billplz')"
                                        class="w-full flex items-center gap-4 px-6 py-4 text-left transition-colors
                                               {{ $paymentMethod === 'billplz' ? 'bg-blue-50' : 'hover:bg-gray-50' }}">
                                    <div class="shrink-0 size-10 flex items-center justify-center rounded-xl transition-colors
                                                {{ $paymentMethod === 'billplz' ? 'bg-blue-100' : 'bg-gray-100' }}">
                                        <svg class="size-5 transition-colors {{ $paymentMethod === 'billplz' ? 'text-blue-600' : 'text-gray-400' }}"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="text-sm font-semibold text-gray-800">Online Payment</span>
                                            <span class="text-xs px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full font-medium">via BillPlz</span>
                                            <span class="text-xs px-2 py-0.5 bg-gray-100 text-gray-500 rounded-full">FPX · Card</span>
                                        </div>
                                        <p class="text-xs text-gray-400 mt-0.5">Online banking or credit/debit card</p>
                                    </div>
                                    <div class="shrink-0 flex items-center gap-2">
                                        @if($paymentMethod === 'billplz')
                                            <span class="size-5 inline-flex items-center justify-center bg-blue-600 rounded-full">
                                                <svg class="size-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </span>
                                        @endif
                                        <svg class="size-4 text-gray-400 transition-transform duration-300 {{ $paymentMethod === 'billplz' ? 'rotate-180' : '' }}"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </button>
                                <div x-show="$wire.paymentMethod === 'billplz'"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 -translate-y-2"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 translate-y-0"
                                     x-transition:leave-end="opacity-0 -translate-y-2"
                                     class="px-6 pb-5 bg-blue-50 border-t border-blue-100">
                                    <div class="pt-4 flex items-start gap-3 text-xs text-blue-700">
                                        <svg class="shrink-0 size-4 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                        </svg>
                                        <div>
                                            <p class="font-medium mb-1">Secure Payment via BillPlz</p>
                                            <p class="text-blue-600">You'll be redirected to BillPlz to complete your payment. Supports FPX (Maybank, CIMB, RHB, etc.) and Visa/Mastercard.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ── PayPal ── --}}
                            <div>
                                <button type="button"
                                        wire:click="$set('paymentMethod', 'paypal')"
                                        class="w-full flex items-center gap-4 px-6 py-4 text-left transition-colors
                                               {{ $paymentMethod === 'paypal' ? 'bg-blue-50' : 'hover:bg-gray-50' }}">
                                    <div class="shrink-0 size-10 flex items-center justify-center rounded-xl transition-colors
                                                {{ $paymentMethod === 'paypal' ? 'bg-[#e8f0fe]' : 'bg-gray-100' }}">
                                        <svg class="size-5 transition-colors {{ $paymentMethod === 'paypal' ? 'text-[#003087]' : 'text-gray-400' }}"
                                             viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M7.076 21.337H2.47a.641.641 0 0 1-.633-.74L4.944.901C5.026.382 5.474 0 5.998 0h7.46c2.57 0 4.578.543 5.69 1.81 1.01 1.15 1.304 2.42 1.012 4.287-.023.143-.047.288-.077.437-.983 5.05-4.349 6.797-8.647 6.797h-2.19c-.524 0-.968.382-1.05.9l-1.12 7.106zm14.146-14.42a3.35 3.35 0 0 0-.607-.541c-.013.076-.026.175-.041.254-.93 4.778-4.005 7.201-9.138 7.201h-2.19a.563.563 0 0 0-.556.479l-1.187 7.527h-.506l-.24 1.516a.56.56 0 0 0 .554.647h3.882c.46 0 .85-.334.922-.788.06-.26.76-4.852.816-5.09a.932.932 0 0 1 .923-.788h.58c3.76 0 6.705-1.528 7.565-5.946.36-1.847.174-3.388-.777-4.471z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="text-sm font-semibold text-gray-800">PayPal</span>
                                            <span class="text-xs px-2 py-0.5 bg-[#e8f0fe] text-[#003087] rounded-full font-medium">AUD</span>
                                            <span class="text-xs px-2 py-0.5 bg-gray-100 text-gray-500 rounded-full">Australia</span>
                                        </div>
                                        <p class="text-xs text-gray-400 mt-0.5">PayPal account or card in Australian Dollars</p>
                                    </div>
                                    <div class="shrink-0 flex items-center gap-2">
                                        @if($paymentMethod === 'paypal')
                                            <span class="size-5 inline-flex items-center justify-center bg-blue-600 rounded-full">
                                                <svg class="size-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </span>
                                        @endif
                                        <svg class="size-4 text-gray-400 transition-transform duration-300 {{ $paymentMethod === 'paypal' ? 'rotate-180' : '' }}"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </button>
                                <div x-show="$wire.paymentMethod === 'paypal'"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 -translate-y-2"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 translate-y-0"
                                     x-transition:leave-end="opacity-0 -translate-y-2"
                                     class="px-6 pb-5 bg-blue-50 border-t border-blue-100">
                                    <div class="pt-4 flex items-start gap-3 text-xs text-blue-700">
                                        <svg class="shrink-0 size-4 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                        </svg>
                                        <div>
                                            <p class="font-medium mb-1">Secure Payment via PayPal</p>
                                            <p class="text-blue-600">You'll be redirected to PayPal to complete your payment in AUD. Pay with your PayPal balance, bank account, or any card.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ── Cash on Delivery ── --}}
                            <div>
                                <button type="button"
                                        wire:click="$set('paymentMethod', 'cod')"
                                        class="w-full flex items-center gap-4 px-6 py-4 text-left transition-colors
                                               {{ $paymentMethod === 'cod' ? 'bg-green-50' : 'hover:bg-gray-50' }}">
                                    <div class="shrink-0 size-10 flex items-center justify-center rounded-xl transition-colors
                                                {{ $paymentMethod === 'cod' ? 'bg-green-100' : 'bg-gray-100' }}">
                                        <svg class="size-5 transition-colors {{ $paymentMethod === 'cod' ? 'text-green-600' : 'text-gray-400' }}"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="text-sm font-semibold text-gray-800">Cash on Delivery</span>
                                            <span class="text-xs px-2 py-0.5 bg-green-100 text-green-700 rounded-full font-medium">COD</span>
                                        </div>
                                        <p class="text-xs text-gray-400 mt-0.5">Pay cash when your order arrives</p>
                                    </div>
                                    <div class="shrink-0 flex items-center gap-2">
                                        @if($paymentMethod === 'cod')
                                            <span class="size-5 inline-flex items-center justify-center bg-green-600 rounded-full">
                                                <svg class="size-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </span>
                                        @endif
                                        <svg class="size-4 text-gray-400 transition-transform duration-300 {{ $paymentMethod === 'cod' ? 'rotate-180' : '' }}"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </button>
                                <div x-show="$wire.paymentMethod === 'cod'"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 -translate-y-2"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 translate-y-0"
                                     x-transition:leave-end="opacity-0 -translate-y-2"
                                     class="px-6 pb-5 bg-green-50 border-t border-green-100">
                                    <div class="pt-4 flex items-start gap-3 text-xs text-green-700">
                                        <svg class="shrink-0 size-4 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                        </svg>
                                        <div>
                                            <p class="font-medium mb-1">Cash on Delivery</p>
                                            <p class="text-green-600">No online payment needed. Have the exact amount ready when your order is delivered.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Submit -->
                    <button type="submit"
                            wire:loading.attr="disabled"
                            class="w-full py-4 px-6 inline-flex justify-center items-center gap-2 text-base font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors shadow-sm disabled:opacity-70">
                        <span wire:loading.remove>Place Order</span>
                        <span wire:loading>Processing...</span>
                        <svg wire:loading.remove class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <svg wire:loading class="animate-spin size-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                    </button>
                </form>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="lg:col-span-2">
                <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm sticky top-24">
                    <h2 class="text-base font-bold text-gray-900 mb-5">Your Order</h2>
                    <div class="space-y-3 mb-4">
                        @foreach($items as $item)
                            <div class="flex justify-between items-start text-sm">
                                <div class="flex-1 min-w-0 pr-3">
                                    <p class="font-medium text-gray-800 truncate">{{ $item->product->name }}</p>
                                    <p class="text-gray-400 text-xs mt-0.5">× {{ $item->quantity }}</p>
                                </div>
                                <span class="font-semibold text-gray-900 shrink-0">$ {{ number_format($item->total, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="border-t border-gray-100 pt-4 space-y-2 text-sm">
                        <div class="flex justify-between text-gray-500">
                            <span>Subtotal</span>
                            <span>$ {{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-500">
                            <span>Shipping</span>
                            @if($this->shippingFee > 0)
                                <span>$ {{ number_format($this->shippingFee, 2) }}</span>
                            @else
                                <span class="text-green-600 font-medium">Free</span>
                            @endif
                        </div>
                        <div class="flex justify-between text-base font-bold text-gray-900 pt-2 border-t border-gray-100">
                            <span>Total</span>
                            <span>$ {{ number_format($this->totalWithShipping, 2) }}</span>
                        </div>
                    </div>
                    <div class="mt-4 p-3 bg-blue-50 rounded-xl text-xs text-blue-700 flex items-start gap-2">
                        <svg class="shrink-0 size-4 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                        </svg>
                        Your payment info is secure and encrypted.
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
