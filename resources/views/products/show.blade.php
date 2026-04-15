@extends('layouts.app')

@php
    use Illuminate\Support\Str;

    $siteName   = \App\Models\Setting::get('site.name', config('app.name', 'MyShop'));
    $productUrl = url('/products/' . $product->slug);
    $imageUrl   = $product->hasMedia('images') ? $product->getFirstMediaUrl('images') : null;
    $plainDesc  = strip_tags($product->description ?? '');
    // Respect per-product SEO overrides set in admin
    $metaTitle  = $product->seo_title ?: $product->name;
    $metaDesc   = $product->seo_description ?: ($product->excerpt ?? Str::limit($plainDesc, 160));

    // Schema.org availability
    $availability = (! $product->is_stockable || $product->stock > 0)
        ? 'https://schema.org/InStock'
        : 'https://schema.org/OutOfStock';

    // Product structured data
    $productSchema = array_filter([
        '@context'    => 'https://schema.org/',
        '@type'       => 'Product',
        'name'        => $product->name,
        'description' => $metaDesc ?: null,
        'sku'         => $product->sku,
        'image'       => $imageUrl ? [$imageUrl] : null,
        'brand'       => [
            '@type' => 'Brand',
            'name'  => $siteName,
        ],
        'offers' => [
            '@type'         => 'Offer',
            'url'           => $productUrl,
            'priceCurrency' => 'AUD',
            'price'         => number_format((float) $product->price, 2, '.', ''),
            'availability'  => $availability,
            'itemCondition' => 'https://schema.org/NewCondition',
            'seller'        => [
                '@type' => 'Organization',
                'name'  => $siteName,
            ],
        ],
    ]);

    // Build category-aware breadcrumb: Home → [Category ancestors] → [Category] → Product
    // Uses the product's first category (if any) for a logical hierarchy
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
@if($product->noindex)
    @push('head')<meta name="robots" content="noindex, nofollow">@endpush
@endif

@push('head')
    {{-- Canonical --}}
    <link rel="canonical" href="{{ $productUrl }}">

    {{-- Open Graph --}}
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
    @endif

    {{-- Twitter Card --}}
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="{{ $product->name }}">
    <meta name="twitter:description" content="{{ $metaDesc }}">
    @if($imageUrl)
        <meta name="twitter:image" content="{{ $imageUrl }}">
    @endif

    {{-- JSON-LD: Product --}}
    <script type="application/ld+json">
        {!! json_encode($productSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>

    {{-- JSON-LD: Breadcrumb --}}
    <script type="application/ld+json">
        {!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
@endpush

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        {{-- Breadcrumb nav — mirrors breadcrumb schema exactly --}}
        <nav class="flex items-center flex-wrap gap-1.5 text-sm text-gray-500 mb-8">
            <a href="/" class="hover:text-blue-600 transition-colors">Home</a>

            @if($primaryCategory)
                @foreach($primaryCategory->breadcrumb() as $crumb)
                    <svg class="size-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <a href="/category/{{ $crumb->slug }}" class="hover:text-blue-600 transition-colors">{{ $crumb->name }}</a>
                @endforeach
            @else
                <svg class="size-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="/products" class="hover:text-blue-600 transition-colors">Products</a>
            @endif

            <svg class="size-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-900 font-medium truncate">{{ $product->name }}</span>
        </nav>

        <livewire:product-detail :product="$product" />
    </div>
@endsection