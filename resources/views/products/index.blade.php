@extends('layouts.app')

@section('title', 'All Products')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-sm text-gray-500 mb-8">
            <a href="/" class="hover:text-blue-600 transition-colors">Home</a>
            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-800 font-medium">Products</span>
        </nav>

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">All Products</h1>
            <p class="text-gray-500 mt-1">Browse our full collection</p>
        </div>

        <livewire:product-catalog />
    </div>
@endsection
