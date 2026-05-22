@extends('layouts.app')
@section('title', 'Campaigns - OutReach')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="text-center mb-12">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">Active Campaigns</h1>
        <p class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">Browse current referral campaigns and start earning rewards by sharing with friends.</p>
    </div>
    @if($campaigns->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($campaigns as $campaign)
        <div class="group p-6 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 card-hover">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-violet-500/10 to-indigo-500/10 dark:from-violet-500/20 dark:to-indigo-500/20 flex items-center justify-center">
                    <i data-lucide="megaphone" class="w-6 h-6 text-violet-600 dark:text-violet-400"></i>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400"><span class="status-dot active"></span>Active</span>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors">{{ $campaign->title }}</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">{{ $campaign->description }}</p>
            @if($campaign->businessUser)
            <p class="text-xs text-gray-500 mb-3"><i data-lucide="building-2" class="w-3 h-3 inline"></i> {{ $campaign->businessUser->company_name ?? $campaign->businessUser->name }}</p>
            @endif
            <div class="flex items-center gap-4 mb-4 text-sm">
                <span class="inline-flex items-center gap-1 text-amber-600 dark:text-amber-400 font-semibold"><i data-lucide="coins" class="w-4 h-4"></i>{{ $campaign->reward_value }} {{ ucfirst($campaign->reward_type) }}</span>
                <span class="text-gray-400">•</span>
                <span class="text-gray-500">Ends {{ \Carbon\Carbon::parse($campaign->end_date)->diffForHumans() }}</span>
            </div>
            <a href="{{ route('campaign.detail', $campaign->_id) }}" class="inline-flex items-center gap-2 w-full justify-center px-4 py-2.5 rounded-xl text-sm font-semibold text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-950/50 hover:bg-violet-100 dark:hover:bg-violet-900/50 transition-colors group-hover:bg-gradient-to-r group-hover:from-violet-600 group-hover:to-indigo-600 group-hover:text-white">
                View Details <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-20">
        <div class="w-16 h-16 rounded-2xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center mx-auto mb-4"><i data-lucide="megaphone" class="w-8 h-8 text-gray-400"></i></div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No Active Campaigns</h3>
        <p class="text-gray-500">Check back later for new referral campaigns!</p>
    </div>
    @endif
</div>
@endsection
