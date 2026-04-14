<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
    @foreach(config('themes', []) as $key => $theme)
        <div class="rounded-xl border-2 overflow-hidden transition-all {{ ($this->data['active_theme'] ?? 'default') === $key ? 'border-primary-500 ring-2 ring-primary-500/20' : 'border-gray-200' }}">
            {{-- Color preview bar --}}
            <div class="h-24 flex flex-col justify-end p-3" style="background: {{ $theme['colors']['surface-dark'] }}">
                <div class="flex gap-2 mb-2">
                    @foreach(['primary', 'primary-dark', 'primary-light', 'accent'] as $color)
                        <div class="size-5 rounded-full border border-white/20" style="background: {{ $theme['colors'][$color] }}" title="{{ $color }}"></div>
                    @endforeach
                </div>
                <p class="text-xs font-bold text-white">{{ $theme['name'] }}</p>
            </div>
            {{-- Info --}}
            <div class="p-3 bg-white">
                <p class="text-xs text-gray-500">{{ $theme['description'] }}</p>
                <div class="mt-2 flex flex-wrap gap-1">
                    <span class="inline-flex text-[10px] px-1.5 py-0.5 bg-gray-100 text-gray-600 rounded">{{ $theme['hero_style'] }} hero</span>
                    <span class="inline-flex text-[10px] px-1.5 py-0.5 bg-gray-100 text-gray-600 rounded">{{ $theme['product_card_style'] }} cards</span>
                </div>
                <p class="mt-2 text-[10px] text-gray-400" style="font-family: {{ $theme['fonts']['heading'] }}">
                    Heading Font Preview — Aa Bb Cc
                </p>
            </div>
        </div>
    @endforeach
</div>
