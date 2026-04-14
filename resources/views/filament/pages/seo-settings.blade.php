<x-filament-panels::page>

    {{-- Sitemap Status Cards --}}
    @php
        $generator    = app(\App\Services\SitemapGenerator::class);
        $lastGen      = $generator->lastGenerated();
        $urlCount     = $generator->urlCount();
        $sitemapReady = $generator->exists();
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 flex items-center gap-4">
            <div class="shrink-0 size-10 rounded-full flex items-center justify-center {{ $sitemapReady ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400' }}">
                <x-heroicon-o-document-text class="size-5" />
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Sitemap Status</p>
                <p class="mt-0.5 font-semibold text-gray-900 dark:text-white">
                    {{ $sitemapReady ? 'Generated' : 'Not generated' }}
                </p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 flex items-center gap-4">
            <div class="shrink-0 size-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                <x-heroicon-o-link class="size-5" />
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Indexed URLs</p>
                <p class="mt-0.5 font-semibold text-gray-900 dark:text-white">{{ $urlCount > 0 ? number_format($urlCount) : '—' }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 flex items-center gap-4">
            <div class="shrink-0 size-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                <x-heroicon-o-clock class="size-5" />
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Last Generated</p>
                <p class="mt-0.5 font-semibold text-gray-900 dark:text-white text-sm">
                    {{ $lastGen ? \Carbon\Carbon::parse($lastGen)->diffForHumans() : 'Never' }}
                </p>
            </div>
        </div>
    </div>

    {{-- Quick Tool Links --}}
    <div class="mb-6 flex flex-wrap gap-3 text-sm">
        <a href="https://search.google.com/search-console" target="_blank"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-gray-600 hover:text-blue-600 hover:border-blue-300 transition-colors">
            <x-heroicon-o-arrow-top-right-on-square class="size-3.5" />
            Google Search Console
        </a>
        <a href="https://www.bing.com/webmasters" target="_blank"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-gray-600 hover:text-blue-600 hover:border-blue-300 transition-colors">
            <x-heroicon-o-arrow-top-right-on-square class="size-3.5" />
            Bing Webmaster Tools
        </a>
        <a href="https://search.google.com/test/rich-results" target="_blank"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-gray-600 hover:text-blue-600 hover:border-blue-300 transition-colors">
            <x-heroicon-o-arrow-top-right-on-square class="size-3.5" />
            Rich Results Test
        </a>
        <a href="https://developers.facebook.com/tools/debug/" target="_blank"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-gray-600 hover:text-blue-600 hover:border-blue-300 transition-colors">
            <x-heroicon-o-arrow-top-right-on-square class="size-3.5" />
            Facebook OG Debugger
        </a>
        <a href="https://cards-dev.twitter.com/validator" target="_blank"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-gray-600 hover:text-blue-600 hover:border-blue-300 transition-colors">
            <x-heroicon-o-arrow-top-right-on-square class="size-3.5" />
            Twitter Card Validator
        </a>
    </div>

    {{-- Settings Form --}}
    <x-filament-panels::form wire:submit="save">
        {{ $this->form }}
    </x-filament-panels::form>

</x-filament-panels::page>
