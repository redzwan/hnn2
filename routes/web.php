<?php

use App\Http\Controllers\BillplzController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\PageController;
use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use App\Models\Setting;
use App\Services\SitemapGenerator;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

// ===== STOREFRONT =====
Route::get('/', function () {
    $featured = Product::actives()->with('media')->latest()->take(8)->get();
    $categories = Category::active()->with('products')->orderBy('sort_order')->get();
    $pages = Page::active()->orderBy('sort_order')->get();

    // Homepage settings from admin panel
    $homepage = [
        'hero_title'               => Setting::get('homepage.hero_title', 'Shop Smarter, Live Better'),
        'hero_subtitle'            => Setting::get('homepage.hero_subtitle', 'Discover premium products at unbeatable prices. Fast shipping, easy returns, and exceptional quality — every time.'),
        'hero_image'               => Setting::get('homepage.hero_image'),
        'hero_images'              => json_decode(Setting::get('homepage.hero_images', '[]'), true) ?: [],
        'hero_badge'               => Setting::get('homepage.hero_badge', 'New arrivals this week'),
        'hero_cta_text'            => Setting::get('homepage.hero_cta_text', 'Shop Now'),
        'hero_cta_url'             => Setting::get('homepage.hero_cta_url', '/products'),
        'hero_secondary_text'      => Setting::get('homepage.hero_secondary_text', 'Join Free'),
        'hero_secondary_url'       => Setting::get('homepage.hero_secondary_url', '/register'),
        'hero_height'              => Setting::get('homepage.hero_height', '90'),
        'hero_overlay_opacity'     => Setting::get('homepage.hero_overlay_opacity', '40'),
        'hero_transition_style'    => Setting::get('homepage.hero_transition_style', 'fade'),
        'hero_transition_duration' => Setting::get('homepage.hero_transition_duration', '1500'),
        'hero_slide_interval'      => Setting::get('homepage.hero_slide_interval', '7'),
        'show_features'            => (bool) Setting::get('homepage.show_features', true),
        'features'                 => json_decode(Setting::get('homepage.features', ''), true) ?: [
            ['icon' => 'truck',   'title' => 'Free Shipping',  'text' => 'On orders over $200'],
            ['icon' => 'refresh', 'title' => 'Easy Returns',   'text' => '30-day return policy'],
            ['icon' => 'shield',  'title' => 'Secure Payment', 'text' => '100% protected'],
            ['icon' => 'support', 'title' => '24/7 Support',   'text' => 'Always here to help'],
        ],
        'show_featured_products'   => (bool) Setting::get('homepage.show_featured_products', true),
        'featured_title'           => Setting::get('homepage.featured_title', 'New Arrivals'),
        'featured_subtitle'        => Setting::get('homepage.featured_subtitle', 'Featured'),
        'show_cta_banner'          => (bool) Setting::get('homepage.show_cta_banner', true),
        'cta_title'                => Setting::get('homepage.cta_title', 'Ready to start shopping?'),
        'cta_subtitle'             => Setting::get('homepage.cta_subtitle', 'Join thousands of happy customers and enjoy premium products delivered fast.'),
        'show_category_pills'      => (bool) Setting::get('homepage.show_category_pills', true),
    ];

    return view('home', compact('featured', 'categories', 'pages', 'homepage'));
});

Route::get('/category/{slug}', function ($slug) {
    $category = Category::where('slug', $slug)->firstOrFail();

    return view('category', compact('category'));
});

Route::get('/products', function () {
    return view('products.index');
});

Route::get('/products/{slug}', function ($slug) {
    $product = Product::where('slug', $slug)->with('media', 'categories.parent')->firstOrFail();

    return view('products.show', compact('product'));
});

// ===== CART =====
Route::get('/cart', function () {
    return view('cart.index');
});

// ===== CHECKOUT =====
Route::get('/checkout', function () {
    return view('checkout.index');
})->middleware('auth');

// ===== AUTH =====
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');

    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');

    Route::get('/reset-password/{token}', function (string $token) {
        return view('auth.reset-password', ['token' => $token]);
    })->name('password.reset');
});

Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
})->middleware('auth')->name('logout');

// ===== BILLPLZ PAYMENT =====
Route::get('/checkout/billplz/return/{order}', [BillplzController::class, 'paymentReturn'])->name('billplz.return');
Route::post('/api/billplz/webhook', [BillplzController::class, 'callback'])->name('billplz.callback')->withoutMiddleware([VerifyCsrfToken::class]);

// ===== PAYPAL PAYMENT =====
Route::get('/checkout/paypal/return/{order}', [PaypalController::class, 'paymentReturn'])->name('paypal.return');
Route::get('/checkout/paypal/cancel/{order}', [PaypalController::class, 'paymentCancel'])->name('paypal.cancel');

// ===== SEO FILES =====
Route::get('/sitemap.xml', function () {
    $generator = app(SitemapGenerator::class);

    if (! $generator->exists()) {
        $generator->generate();
    }

    return response()->file(public_path('sitemap.xml'), ['Content-Type' => 'application/xml']);
});

Route::get('/robots.txt', function () {
    $noindex = (bool) Setting::get('seo.noindex_site', false);
    $content = $noindex
        ? "User-agent: *\nDisallow: /"
        : Setting::get('seo.robots_txt', implode("\n", [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin',
            'Disallow: /cart',
            'Disallow: /checkout',
            '',
            'Sitemap: '.url('/sitemap.xml'),
        ]));

    return response($content, 200, ['Content-Type' => 'text/plain']);
});

// ===== PAGES =====
// Must be last — catch-all slug route
Route::get('/{slug}', [PageController::class, 'show'])->where('slug', '[a-z0-9-]+');
