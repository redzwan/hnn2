@extends('layouts.app')

@section('title', $paid ? 'Payment Successful' : 'Payment Status')

@section('content')
    <div class="max-w-lg mx-auto px-4 py-20 text-center">
        @if($paid)
            <div class="inline-flex items-center justify-center size-20 bg-green-100 rounded-full mb-6">
                <svg class="size-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-3">Payment Received!</h1>
            <p class="text-gray-500 text-lg mb-2">Thank you for your purchase.</p>
            <p class="text-sm text-gray-400 mb-2">
                Order number: <span class="font-mono font-semibold text-gray-700">{{ $orderNumber }}</span>
            </p>
            @if($billId)
                <p class="text-sm text-gray-400 mb-8">
                    Bill ID: <span class="font-mono text-gray-600">{{ $billId }}</span>
                </p>
            @endif
            <p class="text-sm text-gray-500 mb-8">
                Your order is being processed. You will receive a confirmation email shortly.
            </p>
        @else
            <div class="inline-flex items-center justify-center size-20 bg-yellow-100 rounded-full mb-6">
                <svg class="size-10 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-3">Payment Pending</h1>
            <p class="text-gray-500 text-lg mb-2">Your payment has not been confirmed yet.</p>
            <p class="text-sm text-gray-400 mb-8">
                Order number: <span class="font-mono font-semibold text-gray-700">{{ $orderNumber }}</span>
            </p>
            <p class="text-sm text-gray-500 mb-8">
                If you've completed the payment, it may take a few moments to verify. Please contact us if you need assistance.
            </p>
        @endif

        <a href="/products"
           class="inline-flex items-center gap-2 py-3 px-8 font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors">
            Continue Shopping
        </a>
    </div>
@endsection
