{{-- Matches category page card style exactly --}}
@php
    $themeService = app(\App\Services\ThemeService::class);
    $cardTheme = $themeService->active();
@endphp

<div class="group flex flex-col bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1">

    {{-- Image --}}
    <a href="/products/{{ $product->slug }}"
       class="block relative overflow-hidden bg-gradient-to-br from-blue-50 to-indigo-100"
       style="aspect-ratio: 1/1;">
        @if($product->hasMedia('images'))
            <img src="{{ $product->getFirstMediaUrl('images') }}"
                 alt="{{ $product->name }}"
                 class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
        @else
            <div class="absolute inset-0 flex items-center justify-center">
                <svg class="size-16 text-blue-200 group-hover:scale-110 transition-transform duration-300"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
        @endif
    </a>

    {{-- Info --}}
    <div class="flex flex-col flex-1 p-4">
        <p class="text-xs text-gray-400 mb-1">{{ $product->sku }}</p>
        <a href="/products/{{ $product->slug }}" class="block flex-1">
            <h3 class="font-semibold text-gray-900 group-hover:text-primary transition-colors line-clamp-2">
                {{ $product->name }}
            </h3>
        </a>
        <div class="mt-3 flex items-center justify-between">
            <span class="text-lg font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
            <a href="/products/{{ $product->slug }}"
               class="inline-flex items-center gap-1 text-xs font-semibold text-primary hover:text-primary-dark">
                Details
                <svg class="size-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>

</div>
