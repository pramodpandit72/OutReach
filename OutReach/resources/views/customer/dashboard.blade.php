@extends('layouts.app')
@section('title', 'Customer Dashboard - OutReach')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">My Dashboard</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Welcome, {{ $user->name }}!</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('customer.rewards') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/50 hover:bg-amber-100 transition-colors" id="my-rewards-btn"><i data-lucide="gift" class="w-4 h-4"></i> My Rewards</a>
        </div>
    </div>
    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="p-6 rounded-2xl bg-gradient-to-br from-violet-600 to-indigo-600 text-white card-hover shadow-lg shadow-violet-500/25">
            <div class="flex items-center gap-3 mb-2"><i data-lucide="coins" class="w-6 h-6 opacity-80"></i><span class="text-sm opacity-80 font-medium">Total Points</span></div>
            <p class="text-3xl font-black">{{ number_format($totalPoints) }}</p>
        </div>
        <div class="p-6 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 card-hover">
            <div class="flex items-center gap-3 mb-2"><i data-lucide="users" class="w-6 h-6 text-indigo-500"></i><span class="text-sm text-gray-500 font-medium">Successful Referrals</span></div>
            <p class="text-3xl font-black text-gray-900 dark:text-white">{{ $successfulReferrals }}</p>
        </div>
        <div class="p-6 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 card-hover">
            <div class="flex items-center gap-3 mb-2"><i data-lucide="user-plus" class="w-6 h-6 text-emerald-500"></i><span class="text-sm text-gray-500 font-medium">Friends Referred</span></div>
            <p class="text-3xl font-black text-gray-900 dark:text-white">{{ $referredFriends->count() }}</p>
        </div>
    </div>
    {{-- Referral Code --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 mb-8" x-data>
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-3">Your Referral Code</h2>
        <div class="flex items-center gap-3">
            <div class="flex-1 px-4 py-3 rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 font-mono text-lg font-bold text-violet-600 dark:text-violet-400" id="my-referral-code">{{ $user->referral_code }}</div>
            <button onclick="navigator.clipboard.writeText('{{ $user->referral_code }}'); this.textContent='Copied!'; setTimeout(()=>this.textContent='Copy',2000)" class="px-4 py-3 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 shadow-lg shadow-violet-500/25 transition-all" id="copy-code-btn">Copy</button>
        </div>
    </div>
    {{-- Badges --}}
    @if(count($user->badges ?? []) > 0)
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 mb-8">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">My Badges</h2>
        <div class="flex flex-wrap gap-3">
            @foreach($user->badges as $badge)
            <span class="badge badge-violet"><i data-lucide="award" class="w-3.5 h-3.5"></i> {{ $badge }}</span>
            @endforeach
        </div>
    </div>
    @endif
    {{-- Active Campaigns --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800"><h2 class="text-lg font-bold text-gray-900 dark:text-white">Active Campaigns — Share & Earn</h2></div>
        @if($activeCampaigns->count() > 0)
        <div class="divide-y divide-gray-100 dark:divide-gray-800">
            @foreach($activeCampaigns as $campaign)
            <div class="px-6 py-5 flex flex-col sm:flex-row sm:items-center gap-4">
                <div class="flex-1 min-w-0">
                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ $campaign->title }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($campaign->description, 80) }}</p>
                    <div class="flex items-center gap-3 mt-2 text-sm">
                        <span class="text-amber-600 dark:text-amber-400 font-semibold"><i data-lucide="coins" class="w-3.5 h-3.5 inline"></i> {{ $campaign->reward_value }} {{ ucfirst($campaign->reward_type) }}</span>
                        <span class="text-gray-400">•</span>
                        <span class="text-gray-500">Ends {{ \Carbon\Carbon::parse($campaign->end_date)->diffForHumans() }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('campaign.detail', $campaign->_id) }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 transition-colors">View</a>
                    <a href="{{ route('customer.referral', $campaign->_id) }}" class="px-4 py-2 rounded-lg text-sm font-semibold text-white bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 shadow-md transition-all" id="share-campaign-{{ $loop->index }}">Share</a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="px-6 py-12 text-center text-gray-500">No active campaigns right now. Check back soon!</div>
        @endif
    </div>
    {{-- Referred Friends --}}
    @if($referredFriends->count() > 0)
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800"><h2 class="text-lg font-bold text-gray-900 dark:text-white">Friends You Referred</h2></div>
        <div class="divide-y divide-gray-100 dark:divide-gray-800">
            @foreach($referredFriends as $friend)
            <div class="px-6 py-4 flex items-center gap-4">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-violet-500 to-indigo-500 flex items-center justify-center text-white font-bold text-sm">{{ strtoupper(substr($friend->name,0,1)) }}</div>
                <div class="flex-1"><p class="font-medium text-gray-900 dark:text-white">{{ $friend->name }}</p><p class="text-xs text-gray-500">Joined {{ $friend->created_at->diffForHumans() }}</p></div>
                <span class="badge badge-violet text-xs">Referred</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
