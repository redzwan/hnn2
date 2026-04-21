<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} – My Account</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 antialiased">

    <header class="bg-white border-b border-gray-100 shadow-sm">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2 font-bold text-xl text-gray-900 hover:text-primary transition-colors">
                <svg class="size-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                <span>{{ config('app.name') }}</span>
            </a>
            <div class="flex items-center gap-4 text-sm">
                <a href="/products" class="text-gray-500 hover:text-gray-900 transition-colors">Shop</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-gray-500 hover:text-red-600 transition-colors">Sign Out</button>
                </form>
            </div>
        </nav>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex flex-col md:flex-row gap-8">

            {{-- Sidebar --}}
            <aside class="md:w-60 shrink-0">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 bg-gradient-to-br from-blue-600 to-blue-700">
                        <div class="text-white font-semibold text-sm">{{ auth()->user()->name }}</div>
                        <div class="text-blue-200 text-xs truncate">{{ auth()->user()->email }}</div>
                    </div>
                    <nav class="py-2">
                        @php
                            $links = [
                                ['route' => 'account.dashboard', 'label' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                                ['route' => 'account.orders', 'label' => 'My Orders', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                                ['route' => 'account.favorites', 'label' => 'Favorites', 'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
                                ['route' => 'account.complaints', 'label' => 'Complaints', 'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
                                ['route' => 'account.profile', 'label' => 'Profile', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                            ];
                        @endphp
                        @foreach($links as $link)
                            <a href="{{ route($link['route']) }}"
                               class="flex items-center gap-3 px-5 py-2.5 text-sm transition-colors {{ request()->routeIs($link['route']) ? 'bg-blue-50 text-primary-dark font-medium border-r-2 border-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                <svg class="size-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}"/>
                                </svg>
                                {{ $link['label'] }}
                            </a>
                        @endforeach
                    </nav>
                </div>
            </aside>

            {{-- Main Content --}}
            <main class="flex-1 min-w-0">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
</body>
</html>
