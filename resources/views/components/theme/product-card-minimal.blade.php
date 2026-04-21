{{-- Luxury product card: borderless, larger image, serif name, understated --}}
<div class="group flex flex-col">
    <a href="/products/{{ $product->slug }}" class="block overflow-hidden rounded-xl aspect-[3/4] mb-4" style="background: var(--color-surface)">
        @if($product->hasMedia('images'))
            <img src="{{ $product->getFirstMediaUrl('images') }}"
                 alt="{{ $product->name }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        @else
            <div class="w-full h-full flex items-center justify-center">
                <svg class="size-16 opacity-10" style="color: var(--color-primary)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
        @endif
    </a>
    <div class="space-y-1">
        <a href="/products/{{ $product->slug }}" class="block">
            <h3 class="text-base font-semibold text-gray-900 group-hover:text-primary transition-colors" style="font-family: {{ $theme['fonts']['heading'] }}">
                {{ $product->name }}
            </h3>
        </a>
        <p class="text-sm" style="color: var(--color-primary)">${{ number_format($product->price, 2) }}</p>
    </div>
</div>
