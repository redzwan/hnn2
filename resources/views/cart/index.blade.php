@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-sm text-gray-500 mb-8">
            <a href="/" class="hover:text-blue-600 transition-colors">Home</a>
            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-800 font-medium">Shopping Cart</span>
        </nav>

        <h1 class="text-3xl font-bold text-gray-900 mb-8">Shopping Cart</h1>

        <livewire:cart-page />
    </div>
@endsection
