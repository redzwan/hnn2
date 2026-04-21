@extends('layouts.app')

@section('title', $productsBannerTitle ?? 'All Products')

@section('content')
    {{-- Hero banner --}}
    <section class="relative bg-slate-900 text-white overflow-hidden" style="min-height: 320px;">

        @if(! empty($productsBanner))
            @php
                $bannerUrl = Str::startsWith($productsBanner, ['http://', 'https://'])
                    ? $productsBanner
                    : asset('storage/' . $productsBanner);
            @endphp
            <img src="{{ $bannerUrl }}"
                 alt="{{ $productsBannerTitle ?? 'All Products' }}"
                 class="absolute inset-0 w-full h-full object-cover">
        @endif

        {{-- Overlays — always present, inline styles so no Tailwind purge risk --}}
        <div class="absolute inset-0" style="background: rgba(0,0,0,0.45);"></div>
        <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(15,23,42,0.92) 0%, rgba(15,23,42,0.65) 45%, transparent 100%);"></div>
        <div class="absolute inset-0" style="background: linear-gradient(to top, rgba(15,23,42,0.75) 0%, rgba(15,23,42,0.15) 50%, transparent 100%);"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">
            <nav class="flex items-center gap-2 text-sm text-slate-400 mb-6">
                <a href="/" class="hover:text-white transition-colors">Home</a>
                <span>/</span>
                <span class="text-white font-medium">{{ $productsBannerTitle ?? 'All Products' }}</span>
            </nav>
            <div class="max-w-3xl">
                <h1 class="text-4xl lg:text-5xl font-extrabold leading-tight tracking-tight mb-4">
                    {{ $productsBannerTitle ?? 'All Products' }}
                </h1>
                @if(! empty($productsBannerSubtitle))
                    <p class="text-lg text-slate-300 leading-relaxed">
                        {{ $productsBannerSubtitle }}
                    </p>
                @endif
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <livewire:product-catalog />
    </section>
@endsection
