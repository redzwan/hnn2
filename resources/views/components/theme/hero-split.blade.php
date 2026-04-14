{{-- Luxury: split hero — elegant text left, featured image right --}}
@php
    $heroImage = ! empty($homepage['hero_image']) ? asset('storage/' . $homepage['hero_image']) : null;
@endphp
<section class="bg-surface-dark text-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center py-20 lg:py-28">
            <!-- Text -->
            <div>
                @if(! empty($homepage['hero_badge']))
                    <div class="inline-flex items-center gap-2 border border-primary rounded-full px-4 py-1.5 text-xs tracking-widest uppercase font-medium mb-8 text-primary">
                        {{ $homepage['hero_badge'] }}
                    </div>
                @endif
                <h1 class="text-4xl lg:text-6xl font-bold leading-tight tracking-tight mb-6" style="font-family: {{ $theme['fonts']['heading'] }}">
                    {{ $homepage['hero_title'] }}
                </h1>
                @if(! empty($homepage['hero_subtitle']))
                    <p class="text-lg leading-relaxed mb-10 max-w-md text-on-surface-dark">
                        {{ $homepage['hero_subtitle'] }}
                    </p>
                @endif
                <div class="flex flex-col sm:flex-row gap-4">
                    @if(! empty($homepage['hero_cta_text']))
                        <a href="{{ $homepage['hero_cta_url'] ?? '/products' }}"
                           class="inline-flex items-center justify-center gap-2 py-4 px-8 text-base font-semibold rounded-xl bg-primary text-on-primary transition-colors">
                            {{ $homepage['hero_cta_text'] }}
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                    @endif
                    @if(! empty($homepage['hero_secondary_text']))
                        <a href="{{ $homepage['hero_secondary_url'] ?? '/register' }}"
                           class="inline-flex items-center justify-center gap-2 py-4 px-8 text-base font-semibold border border-primary/50 rounded-xl text-primary-light transition-colors">
                            {{ $homepage['hero_secondary_text'] }}
                        </a>
                    @endif
                </div>

                <!-- Trust badges -->
                <div class="flex items-center gap-6 mt-12 text-xs uppercase tracking-wider text-on-surface-dark">
                    @foreach(['Authentic', 'Premium Quality', 'Imported'] as $badge)
                        <span class="flex items-center gap-1.5">
                            <svg class="size-4 text-primary" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            {{ $badge }}
                        </span>
                    @endforeach
                </div>
            </div>

            <!-- Image -->
            <div class="relative hidden lg:block">
                <div class="aspect-[4/5] rounded-2xl overflow-hidden border border-primary/20">
                    @if($heroImage)
                        <img src="{{ $heroImage }}" alt="{{ $homepage['hero_title'] }}" class="w-full h-full object-cover">
                    @elseif($featured->isNotEmpty() && $featured->first()->hasMedia('images'))
                        <img src="{{ $featured->first()->getFirstMediaUrl('images') }}"
                             alt="{{ $featured->first()->name }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-surface-dark">
                            <svg class="size-24 opacity-20 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                    @endif
                </div>
                <div class="absolute -bottom-4 -left-4 size-24 rounded-2xl -z-10 opacity-30 bg-primary"></div>
            </div>
        </div>
    </div>
</section>
