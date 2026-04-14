@extends('layouts.app')

@php
    $breadcrumb    = $category->breadcrumb(); // ancestors + self (Collection)
    $categoryUrl   = url('/category/' . $category->slug);

    // Schema.org BreadcrumbList: Home → [ancestor chains] → this category
    $schemaItems = [['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')]];
    $pos = 2;
    foreach ($breadcrumb as $crumb) {
        $schemaItems[] = ['@type' => 'ListItem', 'position' => $pos++, 'name' => $crumb->name, 'item' => url('/category/' . $crumb->slug)];
    }

    $breadcrumbSchema = [
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => $schemaItems,
    ];
@endphp

@section('title', $category->name)
@section('meta_description', strip_tags($category->description ?? 'Browse ' . $category->name . ' products.'))

@push('head')
    <link rel="canonical" href="{{ $categoryUrl }}">
    <script type="application/ld+json">
        {!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
@endpush

@section('content')
    <!-- ===== CATEGORY HERO ===== -->
    <section class="relative bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900 text-white overflow-hidden">
        @if($category->banner)
            @php
                $bannerUrl = Str::startsWith($category->banner, ['http://', 'https://'])
                    ? $category->banner
                    : asset('storage/' . $category->banner);
            @endphp
            <img src="{{ $bannerUrl }}" alt="{{ $category->name }}" class="absolute inset-0 w-full h-full object-cover">
            {{-- Base dim so bright areas don't blow out --}}
            <div class="absolute inset-0 bg-black/30"></div>
            {{-- Strong left-side gradient for text legibility --}}
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-900/80 to-transparent"></div>
            {{-- Bottom-to-top gradient for breadcrumb / product count legibility --}}
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent"></div>
        @else
            <div class="absolute inset-0 bg-black/60"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/80 to-transparent"></div>
        @endif

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">

            {{-- Dynamic hierarchical breadcrumb --}}
            <nav aria-label="Breadcrumb" class="flex items-center flex-wrap gap-2 text-sm text-slate-400 mb-6">
                <a href="/" class="hover:text-white transition-colors">Home</a>
                @foreach($breadcrumb as $crumb)
                    <span aria-hidden="true">/</span>
                    @if($loop->last)
                        <span class="text-white font-medium">{{ $crumb->name }}</span>
                    @else
                        <a href="/category/{{ $crumb->slug }}" class="hover:text-white transition-colors">{{ $crumb->name }}</a>
                    @endif
                @endforeach
            </nav>

            <div class="max-w-3xl">
                <h1 class="text-4xl lg:text-5xl font-extrabold leading-tight tracking-tight mb-4">
                    {{ $category->name }}
                </h1>
                @if($category->description)
                    <p class="text-lg text-slate-300 leading-relaxed">
                        {!! strip_tags($category->description) !!}
                    </p>
                @endif
                <div class="flex items-center gap-4 mt-4 text-sm text-slate-400">
                    <span>{{ $category->products->count() }} {{ Str::plural('product', $category->products->count()) }}</span>
                    @if($category->children()->exists())
                        <span>·</span>
                        <span>{{ $category->children()->count() }} {{ Str::plural('subcategory', $category->children()->count()) }}</span>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Subcategories strip --}}
    @if($category->children()->active()->exists())
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10">
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Subcategories</h2>
            <div class="flex flex-wrap gap-3">
                @foreach($category->children()->active()->orderBy('sort_order')->get() as $child)
                    <a href="/category/{{ $child->slug }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-full text-sm font-medium text-gray-700 hover:border-blue-400 hover:text-blue-600 transition-colors shadow-sm">
                        {{ $child->name }}
                        <span class="text-xs text-gray-400">({{ $child->products()->count() }})</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <!-- ===== PRODUCTS GRID ===== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if($category->products->isEmpty())
            <div class="text-center py-20 bg-gray-50 rounded-2xl">
                <p class="text-gray-500 text-lg mb-4">No products in this category yet.</p>
                <a href="/products" class="text-sm text-blue-600 hover:underline">Browse all products →</a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($category->products as $product)
                    <div class="group flex flex-col bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                        <a href="/products/{{ $product->slug }}" class="block overflow-hidden bg-gradient-to-br from-blue-50 to-indigo-100 h-52 flex items-center justify-center">
                            @if($product->hasMedia('images'))
                                <img src="{{ $product->getFirstMediaUrl('images') }}"
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <svg class="size-16 text-blue-200 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            @endif
                        </a>
                        <div class="flex flex-col flex-1 p-4">
                            <p class="text-xs text-gray-400 mb-1">{{ $product->sku }}</p>
                            <a href="/products/{{ $product->slug }}" class="block flex-1">
                                <h3 class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors line-clamp-2">{{ $product->name }}</h3>
                            </a>
                            <div class="mt-3 flex items-center justify-between">
                                <span class="text-lg font-bold text-gray-900">RM {{ number_format($product->price, 2) }}</span>
                                <a href="/products/{{ $product->slug }}"
                                   class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-700">
                                    Details
                                    <svg class="size-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
@endsection
