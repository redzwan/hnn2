@extends('layouts.app')

@section('title', $page->title)

@section('content')
<!-- Banner -->
@if($page->banner)
    <div class="relative h-64 md:h-80 bg-gray-200">
        <img src="{{ Storage::url($page->banner) }}" alt="{{ $page->title }}" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8 w-full">
                <h1 class="text-3xl md:text-4xl font-bold text-white">{{ $page->title }}</h1>
            </div>
        </div>
    </div>
@else
    <div class="bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <h1 class="text-3xl md:text-4xl font-bold text-white">{{ $page->title }}</h1>
        </div>
    </div>
@endif

<!-- Page Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    @if($page->description)
        <p class="text-lg text-gray-600 mb-8">{{ $page->description }}</p>
    @endif

    @if($page->content_blocks)
        <div class="space-y-12">
            @foreach($page->content_blocks as $block)
                <div class="flex flex-col md:flex-row gap-8 {{ $loop->even ? 'md:flex-row-reverse' : '' }}">
                    @if(!empty($block['image']))
                        <div class="md:w-1/2">
                            <img src="{{ Storage::url($block['image']) }}" alt="{{ $block['title'] ?? '' }}" class="w-full rounded-xl shadow-lg">
                        </div>
                    @endif
                    <div class="{{ !empty($block['image']) ? 'md:w-1/2' : 'w-full' }}">
                        @if(!empty($block['title']))
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ $block['title'] }}</h2>
                        @endif
                        <div class="prose prose-lg max-w-none text-gray-600">
                            {!! $block['content'] ?? '' !!}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
