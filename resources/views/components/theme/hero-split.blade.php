{{-- Luxury: split hero — editorial full-height, text left, image right --}}
@php
    $heroImage = ! empty($homepage['hero_image']) ? asset('storage/' . $homepage['hero_image']) : null;
@endphp
<section class="bg-surface-dark text-white overflow-hidden" style="min-height: calc(100vh - 4rem);">
    <div class="grid grid-cols-1 lg:grid-cols-2 h-full" style="min-height: calc(100vh - 4rem);">

        <!-- Text panel -->
        <div class="flex flex-col justify-center px-10 py-20 sm:px-16 lg:px-20 xl:px-28">
            @if(! empty($homepage['hero_badge']))
                <div class="inline-flex items-center gap-2 mb-10">
                    <span class="block w-8 h-px bg-primary"></span>
                    <span class="text-xs tracking-[0.25em] uppercase text-primary font-light">{{ $homepage['hero_badge'] }}</span>
                </div>
            @endif

            <h1 class="text-5xl lg:text-6xl xl:text-7xl font-light leading-[1.05] tracking-tight mb-8"
                style="font-family: {{ $theme['fonts']['heading'] }}; letter-spacing: -0.02em;">
                {{ $homepage['hero_title'] }}
            </h1>

            @if(! empty($homepage['hero_subtitle']))
                <p class="text-base leading-relaxed font-light max-w-sm text-on-surface-dark mb-12"
                   style="letter-spacing: 0.02em;">
                    {{ $homepage['hero_subtitle'] }}
                </p>
            @endif

            <div class="flex flex-col sm:flex-row gap-4">
                @if(! empty($homepage['hero_cta_text']))
                    <a href="{{ $homepage['hero_cta_url'] ?? '/products' }}"
                       class="inline-flex items-center gap-3 py-4 px-8 text-sm font-light tracking-widest uppercase border border-primary/60 text-primary hover:bg-primary hover:text-on-primary transition-all duration-300">
                        {{ $homepage['hero_cta_text'] }}
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                @endif
                @if(! empty($homepage['hero_secondary_text']))
                    <a href="{{ $homepage['hero_secondary_url'] ?? '/register' }}"
                       class="inline-flex items-center justify-center gap-2 py-4 px-8 text-sm font-light tracking-widest uppercase text-on-surface-dark hover:text-white transition-colors duration-300">
                        {{ $homepage['hero_secondary_text'] }}
                    </a>
                @endif
            </div>

            <!-- Trust badges -->
            <div class="flex items-center gap-8 mt-16 text-xs tracking-[0.2em] uppercase font-light text-on-surface-dark/70">
                @foreach(['Authentic', 'Premium Quality', 'Imported'] as $badge)
                    <span class="flex items-center gap-2">
                        <span class="block w-3 h-px bg-primary/60"></span>
                        {{ $badge }}
                    </span>
                @endforeach
            </div>
        </div>

        <!-- Image panel — edge-to-edge, full height -->
        <div class="relative hidden lg:block">
            @if($heroImage)
                <img src="{{ $heroImage }}"
                     alt="{{ $homepage['hero_title'] }}"
                     class="absolute inset-0 w-full h-full object-cover">
            @elseif($featured->isNotEmpty() && $featured->first()->hasMedia('images'))
                <img src="{{ $featured->first()->getFirstMediaUrl('images') }}"
                     alt="{{ $featured->first()->name }}"
                     class="absolute inset-0 w-full h-full object-cover">
            @else
                <div class="absolute inset-0 flex items-center justify-center bg-surface-dark/80">
                    <svg class="size-24 opacity-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            @endif
            <!-- Subtle left-edge fade so text bleeds gracefully -->
            <div class="absolute inset-y-0 left-0 w-24 bg-gradient-to-r from-surface-dark to-transparent pointer-events-none"></div>
        </div>

    </div>
</section>
