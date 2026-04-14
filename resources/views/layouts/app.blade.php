<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        $siteName = \App\Models\Setting::get('site.name', config('app.name', 'Online Store'));
        $themeService = app(\App\Services\ThemeService::class);
        $theme = $themeService->active();
    @endphp
    <title>{{ $siteName }}@hasSection('title') – @yield('title')@endif</title>
    <meta name="description" content="@yield('meta_description', 'Your one-stop express e-commerce destination.')">
    @if(\App\Models\Setting::get('site.favicon'))
    <link rel="icon" href="{{ asset('storage/' . \App\Models\Setting::get('site.favicon')) }}" type="image/x-icon">
    @endif
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="{{ $theme['fonts']['google_import'] }}" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>:root { {!! $themeService->cssVariables() !!} }</style>
    @livewireStyles
    @stack('head')
    <style>
        @keyframes heartbeat {
            0%   { transform: scale(1); }
            14%  { transform: scale(1.3); }
            28%  { transform: scale(1); }
            42%  { transform: scale(1.3); }
            70%  { transform: scale(1); }
            100% { transform: scale(1); }
        }
        .animate-heartbeat {
            display: inline-block;
            animation: heartbeat 1.4s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-surface antialiased" style="font-family: {{ $theme['fonts']['body'] }}">

    <!-- ===== NAVBAR ===== -->
    <header class="sticky top-0 z-50 bg-white border-b border-gray-100 shadow-sm">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                <!-- Logo -->
                <a href="/" class="flex items-center gap-2 font-bold text-xl text-gray-900 hover:text-primary transition-colors">
                    @if(\App\Models\Setting::get('site.logo'))
                        <img src="{{ asset('storage/' . \App\Models\Setting::get('site.logo')) }}" alt="{{ $siteName }}" class="h-8 w-auto object-contain">
                    @else
                        <svg class="size-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    @endif
                    <span>{{ $siteName }}</span>
                </a>

                <!-- Desktop Nav -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="/" class="text-sm font-medium text-gray-600 hover:text-primary transition-colors">Home</a>

                    <!-- Categories Dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" class="flex items-center gap-1 text-sm font-medium text-gray-600 hover:text-primary transition-colors">
                            Products
                            <svg class="size-3 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" x-transition class="absolute left-0 mt-2 w-56 bg-white shadow-lg rounded-xl border border-gray-100 py-2 z-50">
                            <a href="/products" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-600 hover:text-primary hover:bg-primary/5 transition-colors">
                                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                </svg>
                                All Products
                            </a>
                            <div class="border-t border-gray-100 my-2"></div>
                            @if(isset($categories) && $categories->count() > 0)
                                @foreach($categories as $category)
                                    <a href="/category/{{ $category->slug }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-600 hover:text-primary hover:bg-primary/5 transition-colors">
                                        {{ $category->name }}
                                    </a>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <!-- Pages Links -->
                    @if(isset($pages) && $pages->count() > 0)
                        @foreach($pages as $page)
                            <a href="/{{ $page->slug }}" class="text-sm font-medium text-gray-600 hover:text-primary transition-colors">{{ $page->title }}</a>
                        @endforeach
                    @endif
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-3">
                    <!-- Cart -->
                    <a href="/cart" class="relative inline-flex items-center gap-2 py-2 px-3 text-sm font-medium text-gray-600 hover:text-primary hover:bg-primary/5 rounded-lg transition-colors">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span class="hidden sm:block">Cart</span>
                        <livewire:cart-count />
                    </a>

                    <!-- Auth -->
                    @auth
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open" type="button" class="inline-flex items-center gap-2 py-2 px-3 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                {{ Str::limit(auth()->user()->name, 12) }}
                                <svg class="size-3 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-transition class="absolute right-0 mt-2 z-50 min-w-40 bg-white shadow-lg rounded-xl border border-gray-100 p-1">
                                <form method="POST" action="/logout">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="/login" class="py-2 px-3 text-sm font-medium text-gray-600 hover:text-primary hover:bg-primary/5 rounded-lg transition-colors">
                            Login
                        </a>
                        <a href="/register" class="py-2 px-4 text-sm font-semibold text-on-primary bg-primary hover:bg-primary-dark rounded-lg transition-colors shadow-sm">
                            Sign up
                        </a>
                    @endauth

                    <!-- Mobile menu toggle -->
                    <button type="button" class="md:hidden p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg" x-data @click="$dispatch('toggle-mobile-nav')" aria-label="Toggle navigation">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Nav -->
            <div x-data="{ open: false }" @toggle-mobile-nav.window="open = !open" x-show="open" x-transition class="md:hidden pb-4">
                <div class="flex flex-col gap-1 pt-2">
                    <a href="/" class="px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg">Home</a>
                    <a href="/products" class="px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg">Products</a>
                </div>
            </div>
        </nav>
    </header>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
            <div class="flex items-center gap-3 p-4 text-sm text-teal-700 bg-teal-50 border border-teal-200 rounded-xl" role="alert">
                <svg class="shrink-0 size-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
            <div class="flex items-center gap-3 p-4 text-sm text-red-700 bg-red-50 border border-red-200 rounded-xl" role="alert">
                <svg class="shrink-0 size-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9v4a1 1 0 102 0V9a1 1 0 10-2 0zm0-4a1 1 0 112 0 1 1 0 01-2 0z" clip-rule="evenodd"/>
                </svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Page Content -->
    <main>
        @yield('content')
    </main>

    <!-- ===== FOOTER ===== -->
    <footer class="bg-surface-dark text-on-surface-dark mt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-10">
                <div class="col-span-2 md:col-span-1">
                    <a href="/" class="flex items-center gap-2 font-bold text-xl text-white mb-3" style="font-family: {{ $theme['fonts']['heading'] }}">
                        @if(\App\Models\Setting::get('site.logo'))
                            <img src="{{ asset('storage/' . \App\Models\Setting::get('site.logo')) }}" alt="{{ $siteName }}" style="width: 53px; height: auto; border-radius: 7px" class="object-contain">
                        @else
                            <svg class="size-6 text-primary-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        @endif
                        {{ $siteName }}
                    </a>
                    <p class="text-sm text-on-surface-dark/70 leading-relaxed">Your one-stop express e-commerce destination. Quality products, fast delivery.</p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Shop</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/products" class="text-on-surface-dark/70 hover:text-white transition-colors">All Products</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Account</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/login" class="text-on-surface-dark/70 hover:text-white transition-colors">Login</a></li>
                        <li><a href="/register" class="text-on-surface-dark/70 hover:text-white transition-colors">Register</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Admin</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/admin" class="text-on-surface-dark/70 hover:text-white transition-colors">Dashboard</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-white/10 pt-6 flex flex-col sm:flex-row justify-between items-center gap-4 text-sm text-on-surface-dark/50">
                <p>&copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.</p>
                <p class="flex items-center gap-1">
                    Another product made with
                    <span class="animate-heartbeat text-red-400 mx-0.5">&hearts;</span>
                    by <a href="https://www.airevo.my" target="_blank" rel="noopener noreferrer" class="text-on-surface-dark/70 hover:text-white transition-colors">www.airevo.my</a>
                </p>
            </div>
        </div>
    </footer>

    @livewireScripts
    @stack('scripts')
</body>
</html>
