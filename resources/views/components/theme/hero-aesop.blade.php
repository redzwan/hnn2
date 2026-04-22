{{-- Aesop Editorial: full-bleed slideshow hero — minimal, luxury, centered editorial text --}}
@php
    // hero_images may be stored as [{url: "..."}, ...] or ["url", ...]
    $heroImages = collect($homepage['hero_images'] ?? [])
        ->map(fn ($item) => is_array($item) ? ($item['url'] ?? null) : $item)
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

        {{-- Bottom-left fade gradient — fades up and to the right --}}
        <div class="absolute bottom-0 left-0 right-0 h-72 pointer-events-none"
             style="background: linear-gradient(to top, rgba(0,0,0,0.72) 0%, rgba(0,0,0,0.35) 45%, transparent 100%);"></div>
    </div>

    {{-- Bottom-left: title + button —— Studio McGee style --}}
    <div class="absolute bottom-0 left-2 z-10 pl-16 sm:pl-24 pr-10 pb-14 max-w-2xl">

        @if(! empty($homepage['hero_badge']))
            <p class="text-xs font-light tracking-[0.25em] uppercase text-white mb-4"
               style="font-family: {{ $theme['fonts']['body'] }}">
                {{ $homepage['hero_badge'] }}
            </p>
        @endif

        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-light leading-[1.1] text-white mb-4"
            style="font-family: {{ $theme['fonts']['heading'] }}">
            {{ $homepage['hero_title'] }}
        </h1>

        @if(! empty($homepage['hero_subtitle']))
            <p class="text-sm sm:text-base font-light leading-relaxed text-white mb-8 max-w-md"
               style="font-family: {{ $theme['fonts']['body'] }}">
                {{ $homepage['hero_subtitle'] }}
            </p>
        @endif

        @if(! empty($homepage['hero_secondary_text']))
            <a href="{{ $homepage['hero_secondary_url'] ?? '/products' }}"
               class="inline-block text-xs font-light tracking-[0.2em] uppercase border border-white text-white hover:bg-white hover:text-black transition-all duration-300"
               style="font-family: {{ $theme['fonts']['body'] }}; padding: 22px;">
                {{ $homepage['hero_secondary_text'] }}
            </a>
        @endif
    </div>

    {{-- Bottom-right: prev arrow + dots + next arrow --}}
    <div class="absolute bottom-10 right-10 z-10 flex items-center gap-4"
         x-show="images.length > 1"
         x-transition:enter="transition ease-out duration-700"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         style="position: absolute; right: 41px; bottom: 20px;">

        {{-- Prev --}}
        <button @click="advance(-1)"
                class="text-white/70 hover:text-white transition-colors duration-200 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>

        {{-- Dots --}}
        <div class="flex items-center gap-2.5">
            <template x-for="(img, i) in images" :key="i">
                <button @click="go(i)"
                        class="rounded-full transition-all duration-400 focus:outline-none"
                        :class="i === current
                            ? 'w-2.5 h-2.5 bg-white'
                            : 'w-2 h-2 bg-white/40 hover:bg-white/70'">
                </button>
            </template>
        </div>

        {{-- Next --}}
        <button @click="advance(1)"
                class="text-white/70 hover:text-white transition-colors duration-200 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
    </div>
</section>
