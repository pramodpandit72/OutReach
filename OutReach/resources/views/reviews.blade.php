@extends('layouts.app')
@section('title', 'Reviews & Testimonials - OutReach')
@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="text-center mb-12">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">Reviews & Testimonials</h1>
        <p class="text-gray-600 dark:text-gray-400">Real feedback from real advocates on the platform.</p>
    </div>
    @if($reviews->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($reviews as $review)
        <div class="p-6 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 card-hover">
            <div class="flex items-center gap-1 mb-3">
                @for($i=1;$i<=5;$i++)
                <i data-lucide="star" class="w-4 h-4 {{ $i<=$review->rating ? 'text-amber-400 fill-amber-400' : 'text-gray-300 dark:text-gray-600' }}"></i>
                @endfor
            </div>
            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 italic">"{{ $review->comment }}"</p>
            @if($review->campaignData)
            <p class="text-xs text-violet-600 dark:text-violet-400 font-medium mb-3">
                <i data-lucide="megaphone" class="w-3 h-3 inline"></i> {{ $review->campaignData->title }}
            </p>
            @endif
            <div class="flex items-center gap-3 pt-3 border-t border-gray-100 dark:border-gray-800">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-violet-500 to-indigo-500 flex items-center justify-center text-white text-xs font-bold">
                    {{ $review->reviewer ? strtoupper(substr($review->reviewer->name,0,1)) : '?' }}
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $review->reviewer ? $review->reviewer->name : 'Anonymous' }}</p>
                    <p class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-20">
        <div class="w-16 h-16 rounded-2xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center mx-auto mb-4"><i data-lucide="message-square" class="w-8 h-8 text-gray-400"></i></div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No Reviews Yet</h3>
        <p class="text-gray-500">Be the first to share your experience!</p>
    </div>
    @endif
</div>
@endsection
