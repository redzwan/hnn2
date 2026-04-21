@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-sm text-gray-500 mb-8">
            <a href="/" class="hover:text-primary transition-colors">Home</a>
            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <a href="/cart" class="hover:text-primary transition-colors">Cart</a>
            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-800 font-medium">Checkout</span>
        </nav>

        @guest
        <div class="mb-6 p-4 bg-stone-50 border border-stone-200 rounded-xl flex items-center justify-between gap-4">
            <p class="text-sm text-gray-600">Already have an account? Sign in to pre-fill your details.</p>
            <a href="/login" class="shrink-0 text-sm font-semibold text-primary hover:text-primary-dark transition-colors">Sign in &rarr;</a>
        </div>
        @endguest

        <!-- Progress Steps -->
        <div class="flex items-center gap-3 mb-10">
            <div class="flex items-center gap-2">
                <span class="size-6 inline-flex items-center justify-center bg-green-500 text-white text-xs font-bold rounded-full">✓</span>
                <span class="text-sm font-medium text-green-600">Cart</span>
            </div>
            <div class="flex-1 h-0.5 bg-green-200"></div>
            <div class="flex items-center gap-2">
                <span class="size-6 inline-flex items-center justify-center bg-blue-600 text-white text-xs font-bold rounded-full">2</span>
                <span class="text-sm font-medium text-primary">Details</span>
            </div>
            <div class="flex-1 h-0.5 bg-gray-200"></div>
            <div class="flex items-center gap-2">
                <span class="size-6 inline-flex items-center justify-center bg-gray-200 text-gray-500 text-xs font-bold rounded-full">3</span>
                <span class="text-sm font-medium text-gray-400">Confirmation</span>
            </div>
        </div>

        <h1 class="text-3xl font-bold text-gray-900 mb-8">Checkout</h1>

        <livewire:checkout-form />
    </div>
@endsection
