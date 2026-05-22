@extends('layouts.app')
@section('title', 'Analytics - OutReach')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <a href="{{ route('business.dashboard') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-violet-600 mb-4"><i data-lucide="arrow-left" class="w-4 h-4"></i> Back</a>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Overall Analytics</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Performance overview across all your campaigns.</p>
    </div>
    {{-- Overview Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="p-6 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 card-hover">
            <div class="w-10 h-10 rounded-xl bg-violet-100 dark:bg-violet-950/50 flex items-center justify-center mb-3"><i data-lucide="mouse-pointer-click" class="w-5 h-5 text-violet-600 dark:text-violet-400"></i></div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalClicks) }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Clicks</p>
        </div>
        <div class="p-6 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 card-hover">
            <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-950/50 flex items-center justify-center mb-3"><i data-lucide="users" class="w-5 h-5 text-indigo-600 dark:text-indigo-400"></i></div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalReferrals) }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Referrals</p>
        </div>
        <div class="p-6 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 card-hover">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-950/50 flex items-center justify-center mb-3"><i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 dark:text-emerald-400"></i></div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalConversions) }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400">Conversions</p>
        </div>
        <div class="p-6 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 card-hover">
            <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-950/50 flex items-center justify-center mb-3"><i data-lucide="gift" class="w-5 h-5 text-amber-600 dark:text-amber-400"></i></div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalRewards) }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400">Rewards</p>
        </div>
    </div>
    {{-- Campaign Performance Table --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Campaign Performance</h2>
        </div>
        @if($campaignStats->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Campaign</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Clicks</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Referrals</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Conversions</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Conv. Rate</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($campaignStats as $stat)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30">
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $stat['campaign']->title }}</p>
                        </td>
                        <td class="px-6 py-4 text-center font-medium text-gray-900 dark:text-white">{{ $stat['clicks'] }}</td>
                        <td class="px-6 py-4 text-center font-medium text-gray-900 dark:text-white">{{ $stat['referrals'] }}</td>
                        <td class="px-6 py-4 text-center font-medium text-emerald-600 dark:text-emerald-400">{{ $stat['conversions'] }}</td>
                        <td class="px-6 py-4 text-center font-medium text-violet-600 dark:text-violet-400">{{ $stat['clicks'] > 0 ? round(($stat['conversions']/$stat['clicks'])*100, 1) : 0 }}%</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $stat['campaign']->status === 'active' ? 'bg-emerald-100 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400' : ($stat['campaign']->status === 'paused' ? 'bg-amber-100 dark:bg-amber-950/50 text-amber-700' : 'bg-red-100 dark:bg-red-950/50 text-red-700') }}">
                                <span class="status-dot {{ $stat['campaign']->status }}"></span>{{ ucfirst($stat['campaign']->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">No campaigns yet.</div>
        @endif
    </div>
</div>
@endsection
