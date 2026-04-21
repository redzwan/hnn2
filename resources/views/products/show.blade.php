@extends('layouts.app')

@php
    use Illuminate\Support\Str;

    $siteName   = \App\Models\Setting::get('site.name', config('app.name', 'MyShop'));
    $productUrl = url('/products/' . $product->slug);
    $imageUrl   = $product->hasMedia('images') ? $product->getFirstMediaUrl('images') : null;
    $plainDesc  = strip_tags($product->description ?? '');
    $metaTitle  = $product->seo_title ?: $product->name;
    $metaDesc   = $product->seo_description ?: ($product->excerpt ?? Str::limit($plainDesc, 160));

    $availability = (! $product->is_stockable || $product->stock > 0)
        ? 'https://schema.org/InStock'
        : 'https://schema.org/OutOfStock';

    $productSchema = array_filter([
        '@context'    => 'https://schema.org/',
        '@type'       => 'Product',
        'name'        => $product->name,
        'description' => $metaDesc ?: null,
        'sku'         => $product->sku,
        'image'       => $imageUrl ? [$imageUrl] : null,
        'brand'       => ['@type' => 'Brand', 'name' => $siteName],
        'offers' => [
            '@type'         => 'Offer',
            'url'           => $productUrl,
            'priceCurrency' => 'AUD',
            'price'         => number_format((float) $product->price, 2, '.', ''),
            'availability'  => $availability,
            'itemCondition' => 'https://schema.org/NewCondition',
            'seller'        => ['@type' => 'Organization', 'name' => $siteName],
        ],
    ]);

    $primaryCategory = $product->categories->first();
    $breadcrumbItems = [['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')]];
    $pos = 2;
    if ($primaryCategory) {
        foreach ($primaryCategory->breadcrumb() as $crumb) {
            $breadcrumbItems[] = ['@type' => 'ListItem', 'position' => $pos++, 'name' => $crumb->name, 'item' => url('/category/' . $crumb->slug)];
        }
    } else {
        $breadcrumbItems[] = ['@type' => 'ListItem', 'position' => $pos++, 'name' => 'Products', 'item' => url('/products')];
    }
    $breadcrumbItems[] = ['@type' => 'ListItem', 'position' => $pos, 'name' => $product->name, 'item' => $productUrl];

    $breadcrumbSchema = [
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => $breadcrumbItems,
    ];
@endphp

@section('title', $metaTitle)
@section('meta_description', $metaDesc)

@if($product->noindex ?? false)
    @push('head')<meta name="robots" content="noindex, nofollow">@endpush
@endif

@push('head')
    <link rel="canonical" href="{{ $productUrl }}">
    <meta property="og:type"        content="product">
    <meta property="og:title"       content="{{ $metaTitle }} – {{ $siteName }}">
    <meta property="og:description" content="{{ $metaDesc }}">
    <meta property="og:url"         content="{{ $productUrl }}">
    <meta property="og:site_name"   content="{{ $siteName }}">
    <meta property="product:price:amount"   content="{{ number_format((float) $product->price, 2, '.', '') }}">
    <meta property="product:price:currency" content="AUD">
    @if($imageUrl)
        <meta property="og:image"     content="{{ $imageUrl }}">
        <meta property="og:image:alt" content="{{ $product->name }}">
        <meta name="twitter:card"     content="summary_large_image">
        <meta name="twitter:image"    content="{{ $imageUrl }}">
    @endif
    <script type="application/ld+json">{!! json_encode($productSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    <script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@section('content')

    {{-- ===== PRODUCT DETAIL ===== --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- Breadcrumb --}}
        <nav aria-label="Breadcrumb" class="flex items-center flex-wrap gap-1.5 text-sm text-gray-500 mb-8">
            <a href="/" class="hover:text-primary transition-colors">Home</a>
            @if($primaryCategory)
                @foreach($primaryCategory->breadcrumb() as $crumb)
                    <svg class="size-4 shrink-0 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <a href="/category/{{ $crumb->slug }}" class="hover:text-primary transition-colors">{{ $crumb->name }}</a>
                @endforeach
            @else
                <svg class="size-4 shrink-0 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="/products" class="hover:text-primary transition-colors">Products</a>
            @endif
            <svg class="size-4 shrink-0 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-900 font-medium truncate max-w-xs">{{ $product->name }}</span>
        </nav>

        <livewire:product-detail :product="$product" />

    </div>

    {{-- ===== RELATED PRODUCTS ===== --}}
    @if(isset($related) && $related->isNotEmpty())
        <section class="border-t border-gray-100 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
                <h2 class="text-xl font-bold text-gray-900 mb-8">You might also like</h2>
                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($related as $rel)
                        @php
                            $relImage = $rel->hasMedia('images') ? $rel->getFirstMediaUrl('images') : null;
                        @endphp
                        <a href="/products/{{ $rel->slug }}"
                           class="group flex flex-col bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                            <div class="relative overflow-hidden bg-gradient-to-br from-blue-50 to-indigo-100" style="aspect-ratio: 1/1;">
                                @if($relImage)
                                    <img src="{{ $relImage }}" alt="{{ $rel->name }}"
                                         class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <svg class="size-16 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex flex-col flex-1 p-4">
                                <p class="text-xs text-gray-400 mb-1">{{ $rel->sku }}</p>
                                <h3 class="text-sm font-semibold text-gray-900 group-hover:text-primary transition-colors leading-snug mb-2 flex-1">
                                    {{ $rel->name }}
                                </h3>
                                <span class="text-base font-bold text-gray-900">${{ number_format($rel->price, 2) }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection
