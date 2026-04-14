{{-- Default Blue: gradient hero --}}
@php
    $heroImage = ! empty($homepage['hero_image']) ? asset('storage/' . $homepage['hero_image']) : null;
@endphp
<section class="relative overflow-hidden text-white bg-surface-dark">
    @if($heroImage)
        <div class="absolute inset-0">
            <img src="{{ $heroImage }}" alt="" class="w-full h-full object-cover opacity-30">
        </div>
    @else
        <!-- Background decoration -->
        <div class="absolute inset-0 opacity-20">
            <div class="absolute top-1/4 left-1/4 size-96 rounded-full blur-3xl bg-primary"></div>
            <div class="absolute bottom-1/4 right-1/4 size-80 rounded-full blur-3xl bg-accent"></div>
        </div>
    @endif

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-28 lg:py-36">
        <div class="max-w-3xl">
            @if(! empty($homepage['hero_badge']))
                <div class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-sm font-medium mb-6 bg-primary/20 border border-primary-light/30 text-primary-light">
                    <span class="size-2 rounded-full animate-pulse bg-primary-light"></span>
                    {{ $homepage['hero_badge'] }}
                </div>
            @endif
            <h1 class="text-5xl lg:text-7xl font-extrabold leading-tight tracking-tight mb-6" style="font-family: {{ $theme['fonts']['heading'] }}">
                {{ $homepage['hero_title'] }}
            </h1>
            @if(! empty($homepage['hero_subtitle']))
                <p class="text-lg lg:text-xl leading-relaxed mb-10 max-w-xl text-on-surface-dark">
                    {{ $homepage['hero_subtitle'] }}
                </p>
            @endif
            <div class="flex flex-col sm:flex-row gap-4">
                @if(! empty($homepage['hero_cta_text']))
                    <a href="{{ $homepage['hero_cta_url'] ?? '/products' }}"
                       class="inline-flex items-center justify-center gap-2 py-4 px-8 text-base font-bold text-on-primary bg-primary hover:bg-primary-dark rounded-2xl transition-colors shadow-lg">
                        {{ $homepage['hero_cta_text'] }}
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                @endif
                @if(! empty($homepage['hero_secondary_text']))
                    <a href="{{ $homepage['hero_secondary_url'] ?? '/register' }}"
                       class="inline-flex items-center justify-center gap-2 py-4 px-8 text-base font-bold border-2 border-white/20 hover:border-white/40 rounded-2xl transition-colors">
                        {{ $homepage['hero_secondary_text'] }}
                    </a>
                @endif
            </div>

            <!-- Stats -->
            <div class="flex flex-wrap gap-8 mt-14">
                <div>
                    <p class="text-3xl font-extrabold text-white">10K+</p>
                    <p class="text-sm mt-0.5 text-on-surface-dark">Happy customers</p>
                </div>
                <div class="border-l border-white/10 pl-8">
                    <p class="text-3xl font-extrabold text-white">500+</p>
                    <p class="text-sm mt-0.5 text-on-surface-dark">Products</p>
                </div>
                <div class="border-l border-white/10 pl-8">
                    <p class="text-3xl font-extrabold text-white">4.9★</p>
                    <p class="text-sm mt-0.5 text-on-surface-dark">Average rating</p>
                </div>
            </div>
        </div>
    </div>
</section>
