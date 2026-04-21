@extends('layouts.app')

@section('title', 'Home')

@php
    $themeService = app(\App\Services\ThemeService::class);
    $theme = $themeService->active();
    $heroStyle = $themeService->heroStyle();
    $cardStyle = $themeService->productCardStyle();

    $featureIcons = [
        'truck' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4',
        'refresh' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15',
        'shield' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
        'support' => 'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z',
        'star' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z',
        'heart' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
        'clock' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        'gift' => 'M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7',
    ];
@endphp

@section('content')
    <!-- ===== HERO ===== -->
    @include('components.theme.hero-' . $heroStyle)

    <!-- ===== FEATURES STRIP ===== -->
    @if($homepage['show_features'] && ! empty($homepage['features']))
        <section class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 lg:grid-cols-{{ min(count($homepage['features']), 4) }} divide-x divide-gray-100">
                    @foreach($homepage['features'] as $feature)
                        <div class="flex items-center gap-4 py-5 px-6">
                            <div class="shrink-0 size-10 rounded-xl flex items-center justify-center bg-primary/10">
                                <svg class="size-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $featureIcons[$feature['icon'] ?? 'star'] ?? $featureIcons['star'] }}"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $feature['title'] }}</p>
                                <p class="text-xs text-gray-500">{{ $feature['text'] ?? '' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- ===== FEATURED PRODUCTS ===== -->
    @if($homepage['show_featured_products'])
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="flex items-end justify-between mb-10">
                <div>
                    <p class="text-sm font-semibold text-primary uppercase tracking-wider mb-2">{{ $homepage['featured_subtitle'] }}</p>
                    <h2 class="text-3xl font-bold text-gray-900" style="font-family: {{ $theme['fonts']['heading'] }}">{{ $homepage['featured_title'] }}</h2>
                </div>
                <a href="/products" class="hidden sm:inline-flex items-center gap-1 text-sm font-semibold text-primary hover:text-primary-dark transition-colors">
                    View all
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            @if($featured->isEmpty())
                <div class="text-center py-20 rounded-2xl bg-surface">
                    <p class="text-gray-500 text-lg mb-4">No products yet.</p>
                    <a href="/admin" class="text-sm text-primary hover:underline">Add products in the admin panel &rarr;</a>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($featured as $product)
                        @include('components.theme.product-card-' . $cardStyle, ['product' => $product])
                    @endforeach
                </div>

                <div class="text-center mt-10">
                    <a href="/products"
                       class="inline-flex items-center gap-2 py-3 px-8 text-sm font-semibold text-primary border-2 border-primary/30 rounded-xl transition-colors hover:bg-primary hover:text-on-primary">
                        See All Products
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
            @endif
        </section>
    @endif

    <!-- ===== CTA BANNER ===== -->
    @if($homepage['show_cta_banner'])
        <section class="text-on-primary bg-primary">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 text-center">
                <h2 class="text-3xl font-bold mb-3" style="font-family: {{ $theme['fonts']['heading'] }}">{{ $homepage['cta_title'] }}</h2>
                <p class="mb-8 text-on-primary/80">{{ $homepage['cta_subtitle'] }}</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/register"
                       class="py-3.5 px-8 font-bold bg-white rounded-xl transition-colors shadow-sm text-primary">
                        Create Free Account
                    </a>
                    <a href="/products"
                       class="py-3.5 px-8 font-bold border-2 border-white/30 hover:border-white text-white rounded-xl transition-colors">
                        Browse Products
                    </a>
                </div>
            </div>
        </section>
    @endif
@endsection
