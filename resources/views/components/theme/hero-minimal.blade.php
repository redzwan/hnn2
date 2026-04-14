{{-- Industrial: minimal hero — clean, professional, compact --}}
<section class="border-b border-gray-200 bg-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
        <div class="max-w-3xl">
            <h1 class="text-3xl lg:text-5xl font-bold text-gray-900 leading-tight tracking-tight mb-4" style="font-family: {{ $theme['fonts']['heading'] }}">
                {{ $homepage['hero_title'] }}
            </h1>
            @if(! empty($homepage['hero_subtitle']))
                <p class="text-lg text-gray-600 leading-relaxed mb-8 max-w-xl">
                    {{ $homepage['hero_subtitle'] }}
                </p>
            @endif
            <div class="flex flex-col sm:flex-row gap-3">
                @if(! empty($homepage['hero_cta_text']))
                    <a href="{{ $homepage['hero_cta_url'] ?? '/products' }}"
                       class="inline-flex items-center justify-center gap-2 py-3 px-6 text-sm font-semibold text-on-primary bg-primary hover:bg-primary-dark rounded-lg transition-colors">
                        {{ $homepage['hero_cta_text'] }}
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                @endif
                @if(! empty($homepage['hero_secondary_text']))
                    <a href="{{ $homepage['hero_secondary_url'] ?? '/register' }}"
                       class="inline-flex items-center justify-center gap-2 py-3 px-6 text-sm font-semibold text-gray-700 bg-white border border-gray-300 hover:border-gray-400 rounded-lg transition-colors">
                        {{ $homepage['hero_secondary_text'] }}
                    </a>
                @endif
            </div>
        </div>

        <!-- Quick stats bar -->
        <div class="flex flex-wrap gap-8 mt-12 pt-8 border-t border-gray-200">
            @foreach([
                ['value' => '500+', 'label' => 'Products in stock'],
                ['value' => '24hr', 'label' => 'Order processing'],
                ['value' => 'Bulk', 'label' => 'Discounts available'],
                ['value' => '100%', 'label' => 'Genuine products'],
            ] as $stat)
                <div>
                    <p class="text-xl font-bold text-gray-900">{{ $stat['value'] }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
