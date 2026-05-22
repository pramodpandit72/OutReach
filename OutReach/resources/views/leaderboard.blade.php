@extends('layouts.app')
@section('title', 'Leaderboard - OutReach')
@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="text-center mb-12">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-100 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 text-sm font-medium mb-4"><i data-lucide="trophy" class="w-4 h-4"></i> Top Advocates</div>
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">Leaderboard</h1>
        <p class="text-gray-600 dark:text-gray-400">The most active referrers on the platform. Share more to climb the ranks!</p>
    </div>
    @if($leaderboard->count() > 0)
    {{-- Top 3 Podium --}}
    @if($leaderboard->count() >= 3)
    <div class="grid grid-cols-3 gap-4 mb-10 max-w-xl mx-auto">
        {{-- 2nd Place --}}
        <div class="text-center pt-8">
            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-gray-300 to-gray-400 flex items-center justify-center text-white text-xl font-black mx-auto mb-3 ring-4 ring-gray-200 dark:ring-gray-700">{{ strtoupper(substr($leaderboard[1]->name,0,1)) }}</div>
            <p class="font-bold text-gray-900 dark:text-white text-sm">{{ $leaderboard[1]->name }}</p>
            <p class="text-sm font-bold text-gray-500">{{ number_format($leaderboard[1]->points) }} pts</p>
            <div class="mt-2 badge badge-silver"><i data-lucide="medal" class="w-3 h-3"></i> 2nd</div>
        </div>
        {{-- 1st Place --}}
        <div class="text-center">
            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-amber-400 to-yellow-500 flex items-center justify-center text-white text-2xl font-black mx-auto mb-3 ring-4 ring-amber-200 dark:ring-amber-800 animate-pulse-glow">{{ strtoupper(substr($leaderboard[0]->name,0,1)) }}</div>
            <p class="font-bold text-gray-900 dark:text-white">{{ $leaderboard[0]->name }}</p>
            <p class="text-sm font-bold text-amber-600">{{ number_format($leaderboard[0]->points) }} pts</p>
            <div class="mt-2 badge badge-gold"><i data-lucide="crown" class="w-3 h-3"></i> 1st</div>
        </div>
        {{-- 3rd Place --}}
        <div class="text-center pt-12">
            <div class="w-14 h-14 rounded-full bg-gradient-to-br from-orange-400 to-amber-600 flex items-center justify-center text-white text-lg font-black mx-auto mb-3 ring-4 ring-orange-200 dark:ring-orange-900">{{ strtoupper(substr($leaderboard[2]->name,0,1)) }}</div>
            <p class="font-bold text-gray-900 dark:text-white text-sm">{{ $leaderboard[2]->name }}</p>
            <p class="text-sm font-bold text-orange-600">{{ number_format($leaderboard[2]->points) }} pts</p>
            <div class="mt-2 badge badge-bronze"><i data-lucide="medal" class="w-3 h-3"></i> 3rd</div>
        </div>
    </div>
    @endif
    {{-- Full List --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
        <div class="divide-y divide-gray-100 dark:divide-gray-800">
            @foreach($leaderboard as $user)
            <div class="px-6 py-4 flex items-center gap-4 {{ $loop->index < 3 ? 'bg-gradient-to-r from-amber-50/50 to-transparent dark:from-amber-950/20' : '' }} hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                <div class="w-8 text-center font-black text-lg {{ $loop->index === 0 ? 'rank-1' : ($loop->index === 1 ? 'rank-2' : ($loop->index === 2 ? 'rank-3' : 'text-gray-400')) }}">
                    @if($loop->index < 3)<i data-lucide="{{ $loop->index === 0 ? 'crown' : 'medal' }}" class="w-5 h-5 mx-auto"></i>@else {{ $user->rank }} @endif
                </div>
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-violet-500 to-indigo-500 flex items-center justify-center text-white font-bold text-sm shrink-0">{{ strtoupper(substr($user->name,0,1)) }}</div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $user->name }}</p>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="text-xs text-gray-500">{{ $user->total_referrals }} referrals</span>
                        @foreach(($user->badges ?? []) as $badge)
                        <span class="badge badge-violet text-[10px] py-0 px-1.5">{{ $badge }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="text-right"><p class="font-bold text-violet-600 dark:text-violet-400">{{ number_format($user->points) }}</p><p class="text-xs text-gray-500">points</p></div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="text-center py-20">
        <div class="w-16 h-16 rounded-2xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center mx-auto mb-4"><i data-lucide="trophy" class="w-8 h-8 text-gray-400"></i></div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No Rankings Yet</h3>
        <p class="text-gray-500">Be the first to earn points and claim the top spot!</p>
    </div>
    @endif
</div>
@endsection
