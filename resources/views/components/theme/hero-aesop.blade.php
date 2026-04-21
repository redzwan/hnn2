{{-- Aesop Editorial: full-bleed slideshow hero — minimal, luxury, centered editorial text --}}
@php
    $heroImages = collect($homepage['hero_images'] ?? [])
        ->filter()
        ->values()
        ->toArray();

    if (empty($heroImages) && ! empty($homepage['hero_image'])) {
        $heroImages = [asset('storage/' . $homepage['hero_image'])];
    }

    $transitionStyle = $homepage['hero_transition_style'] ?? 'fade';
    $transitionMs    = (int) ($homepage['hero_transition_duration'] ?? 1500);
    $intervalSec     = (int) ($homepage['hero_slide_interval'] ?? 7);
    $overlayOpacity  = (int) ($homepage['hero_overlay_opacity'] ?? 40);
    $heroHeight      = (int) ($homepage['hero_height'] ?? 90);
    $imagesJson      = json_encode($heroImages);
    $intervalMs      = $intervalSec * 1000;
@endphp

<section class="relative flex items-center justify-center overflow-hidden bg-surface-dark"
         style="min-height: {{ $heroHeight }}vh;"
         x-data="{
             images: {{ $imagesJson }},
             current: 0,
             previous: -1,
             style: '{{ $transitionStyle }}',
             duration: {{ $transitionMs }},
             wait: {{ $intervalMs }},
             timer: null,
             init() {
                 if (this.images.length > 1) {
                     this.timer = setInterval(() => this.advance(1), this.wait);
                 }
             },
             advance(dir) {
                 this.previous = this.current;
                 this.current = (this.current + dir + this.images.length) % this.images.length;
             },
             go(index) {
                 if (index === this.current) return;
                 clearInterval(this.timer);
                 this.previous = this.current;
                 this.current = index;
                 this.timer = setInterval(() => this.advance(1), this.wait);
             },
             slideStyle(i) {
                 const isActive = i === this.current;
                 const isPrev   = i === this.previous;
                 const t        = `opacity ${this.duration}ms ease, transform ${this.duration}ms ease`;
                 const base     = { position: 'absolute', inset: '0', transition: t };
                 if (this.style === 'slide') {
                     if (isActive) return { ...base, opacity: 1, transform: 'translateX(0%)' };
                     if (isPrev)   return { ...base, opacity: 0, transform: 'translateX(-100%)' };
                     return { ...base, opacity: 0, transform: 'translateX(100%)' };
                 }
                 if (this.style === 'zoom') {
                     const zt = `opacity ${this.duration}ms ease, transform ${this.duration * 6}ms ease`;
                     if (isActive) return { ...base, transition: zt, opacity: 1, transform: 'scale(1.05)' };
                     if (isPrev)   return { ...base, transition: zt, opacity: 0, transform: 'scale(1.0)' };
                     return { ...base, transition: zt, opacity: 0, transform: 'scale(1.0)' };
                 }
                 return { ...base, opacity: isActive ? 1 : 0 };
             }
         }">

    {{-- Background slides --}}
    <div class="absolute inset-0 overflow-hidden">
        <template x-if="images.length === 0">
            <div class="absolute inset-0"
                 style="background: linear-gradient(160deg, #2c2418 0%, #1a1612 60%, #0e0c0a 100%);">
            </div>
        </template>

        <template x-for="(img, i) in images" :key="i">
            <div :style="slideStyle(i)" class="absolute inset-0">
                <img :src="img" alt="" class="w-full h-full object-cover">
            </div>
        </template>

        <div class="absolute inset-0" style="background: rgba(0,0,0,{{ $overlayOpacity / 100 }});"></div>
    </div>

    {{-- Content — centered, editorial --}}
    <div class="relative z-10 text-center text-white px-6 max-w-3xl mx-auto">

        @if(! empty($homepage['hero_badge']))
            <p class="text-xs font-light tracking-[0.3em] uppercase text-white/60 mb-10">
                {{ $homepage['hero_badge'] }}
            </p>
        @endif

        <h1 class="text-5xl sm:text-6xl lg:text-8xl font-light leading-[1.05] tracking-tight mb-8"
            style="font-family: {{ $theme['fonts']['heading'] }}">
            {{ $homepage['hero_title'] }}
        </h1>

        @if(! empty($homepage['hero_subtitle']))
            <p class="text-sm sm:text-base font-light tracking-wide leading-relaxed text-white/70 max-w-md mx-auto mb-12"
               style="font-family: {{ $theme['fonts']['body'] }}">
                {{ $homepage['hero_subtitle'] }}
            </p>
        @endif

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            @if(! empty($homepage['hero_cta_text']))
                <a href="{{ $homepage['hero_cta_url'] ?? '/products' }}"
                   class="inline-block px-10 py-3.5 text-xs font-light tracking-[0.2em] uppercase border border-white/60 text-white hover:bg-white hover:text-surface-dark transition-all duration-300"
                   style="font-family: {{ $theme['fonts']['body'] }}">
                    {{ $homepage['hero_cta_text'] }}
                </a>
            @endif

            @if(! empty($homepage['hero_secondary_text']))
                <a href="{{ $homepage['hero_secondary_url'] ?? '/register' }}"
                   class="inline-block px-10 py-3.5 text-xs font-light tracking-[0.2em] uppercase text-white/60 hover:text-white transition-colors duration-300"
                   style="font-family: {{ $theme['fonts']['body'] }}">
                    {{ $homepage['hero_secondary_text'] }}
                </a>
            @endif
        </div>
    </div>

    {{-- Dot indicators --}}
    <div class="absolute bottom-16 left-1/2 -translate-x-1/2 flex gap-2 z-10"
         x-show="images.length > 1">
        <template x-for="(img, i) in images" :key="i">
            <button @click="go(i)"
                    class="rounded-full transition-all duration-300"
                    :class="i === current ? 'w-6 h-1.5 bg-white' : 'w-1.5 h-1.5 bg-white/40 hover:bg-white/70'">
            </button>
        </template>
    </div>

    {{-- Scroll indicator --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 text-white/30">
        <span class="text-[10px] tracking-[0.25em] uppercase font-light"
              style="font-family: {{ $theme['fonts']['body'] }}">Scroll</span>
        <div class="w-px h-8 bg-white/20 animate-pulse"></div>
    </div>
</section>
