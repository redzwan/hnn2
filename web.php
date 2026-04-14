<?php

use App\Livewire\Account\Complaints;
use App\Livewire\Account\Dashboard;
use App\Livewire\Account\Favorites;
use App\Livewire\Account\Orders;
use App\Livewire\Account\Profile;
use App\Models\Category;
use Illuminate\Support\Facades\Route;
use Vanilo\Foundation\Models\Product;

// ===== STOREFRONT =====
Route::get('/', function () {
    $featured = Product::actives()->with('media')->latest()->take(8)->get();
    $categories = Category::active()->with('products')->orderBy('sort_order')->get();

    return view('home', compact('featured', 'categories'));
});

Route::get('/category/{slug}', function ($slug) {
    $category = Category::where('slug', $slug)
        ->with(['products' => fn ($q) => $q->with('media')])
        ->firstOrFail();

    return view('category', compact('category'));
});

Route::get('/products', function () {
    return view('products.index');
});

Route::get('/products/{slug}', function ($slug) {
    $product = Product::where('slug', $slug)->firstOrFail();

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

// ===== CUSTOMER ACCOUNT =====
Route::middleware(['auth', 'customer'])->prefix('account')->name('account.')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/orders', Orders::class)->name('orders');
    Route::get('/favorites', Favorites::class)->name('favorites');
    Route::get('/complaints', Complaints::class)->name('complaints');
    Route::get('/profile', Profile::class)->name('profile');
});

// ===== AUTH =====
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
});

Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
})->middleware('auth')->name('logout');
