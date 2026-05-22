{{-- Business Dashboard --}}
@extends('layouts.app')

@section('title', 'Business Dashboard - OutReach')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Business Dashboard</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Welcome back, {{ $user->name }}! {{ $user->company_name ? '(' . $user->company_name . ')' : '' }}</p>
        </div>
        <a href="{{ route('business.campaign.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 shadow-lg shadow-violet-500/25 hover:shadow-violet-500/40 transition-all" id="create-campaign-btn">
            <i data-lucide="plus" class="w-4 h-4"></i> Create Campaign
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="p-6 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 card-hover">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-violet-100 dark:bg-violet-950/50 flex items-center justify-center">
                    <i data-lucide="mouse-pointer-click" class="w-6 h-6 text-violet-600 dark:text-violet-400"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white stat-number">{{ number_format($totalClicks) }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Clicks</p>
                </div>
            </div>
        </div>

        <div class="p-6 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 card-hover">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-100 dark:bg-indigo-950/50 flex items-center justify-center">
                    <i data-lucide="users" class="w-6 h-6 text-indigo-600 dark:text-indigo-400"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white stat-number">{{ number_format($totalReferrals) }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Referrals</p>
                </div>
            </div>
        </div>

        <div class="p-6 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 card-hover">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-950/50 flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-6 h-6 text-emerald-600 dark:text-emerald-400"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white stat-number">{{ number_format($totalConversions) }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Conversions</p>
                </div>
            </div>
        </div>

        <div class="p-6 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 card-hover">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-950/50 flex items-center justify-center">
                    <i data-lucide="gift" class="w-6 h-6 text-amber-600 dark:text-amber-400"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white stat-number">{{ number_format($totalRewards) }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Rewards Given</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Campaigns Table --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden mb-8">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-800">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Your Campaigns</h2>
            <a href="{{ route('business.analytics') }}" class="text-sm font-medium text-violet-600 dark:text-violet-400 hover:text-violet-700 flex items-center gap-1" id="view-analytics-link">
                <i data-lucide="bar-chart-3" class="w-4 h-4"></i> Analytics
            </a>
        </div>

        @if($campaigns->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full" id="campaigns-table">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Campaign</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Reward</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Period</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach($campaigns as $campaign)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $campaign->title }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 truncate max-w-xs">{{ Str::limit($campaign->description, 50) }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1 text-sm font-medium text-amber-600 dark:text-amber-400">
                                    <i data-lucide="coins" class="w-3.5 h-3.5"></i>
                                    {{ $campaign->reward_value }} {{ ucfirst($campaign->reward_type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold
                                    @if($campaign->status === 'active') bg-emerald-100 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400
                                    @elseif($campaign->status === 'paused') bg-amber-100 dark:bg-amber-950/50 text-amber-700 dark:text-amber-400
                                    @else bg-red-100 dark:bg-red-950/50 text-red-700 dark:text-red-400
                                    @endif">
                                    <span class="status-dot {{ $campaign->status }}"></span>
                                    {{ ucfirst($campaign->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ \Carbon\Carbon::parse($campaign->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($campaign->end_date)->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('business.campaign.analytics', $campaign->_id) }}" class="p-2 rounded-lg text-gray-400 hover:text-violet-600 hover:bg-violet-50 dark:hover:bg-violet-950/50 transition-colors" title="Analytics">
                                        <i data-lucide="bar-chart-3" class="w-4 h-4"></i>
                                    </a>
                                    <a href="{{ route('business.campaign.edit', $campaign->_id) }}" class="p-2 rounded-lg text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-950/50 transition-colors" title="Edit">
                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                    </a>
                                    <form method="POST" action="{{ route('business.campaign.delete', $campaign->_id) }}" onsubmit="return confirm('Are you sure you want to delete this campaign?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-950/50 transition-colors" title="Delete">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-16 text-center">
                <div class="w-16 h-16 rounded-2xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="megaphone" class="w-8 h-8 text-gray-400"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No Campaigns Yet</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-4">Create your first referral campaign to start growing.</p>
                <a href="{{ route('business.campaign.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-violet-600 to-indigo-600 shadow-lg shadow-violet-500/25">
                    <i data-lucide="plus" class="w-4 h-4"></i> Create Campaign
                </a>
            </div>
        @endif
    </div>

    {{-- Recent Referrals --}}
    @if($recentReferrals->count() > 0)
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Recent Referrals</h2>
        </div>
        <div class="divide-y divide-gray-100 dark:divide-gray-800">
            @foreach($recentReferrals as $referral)
                @php
                    $referrer = \App\Models\User::find($referral->referrer_id);
                    $referred = \App\Models\User::find($referral->referred_id);
                    $refCampaign = \App\Models\Campaign::find($referral->campaign_id);
                @endphp
                <div class="px-6 py-4 flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-violet-500 to-indigo-500 flex items-center justify-center text-white text-sm font-bold shrink-0">
                        {{ $referrer ? strtoupper(substr($referrer->name, 0, 1)) : '?' }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            <span class="font-semibold">{{ $referrer ? $referrer->name : 'Unknown' }}</span>
                            referred
                            <span class="font-semibold">{{ $referred ? $referred->name : 'Unknown' }}</span>
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $refCampaign ? $refCampaign->title : 'Campaign' }} • {{ $referral->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                        {{ $referral->status === 'converted' ? 'bg-emerald-100 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400' : 'bg-amber-100 dark:bg-amber-950/50 text-amber-700 dark:text-amber-400' }}">
                        {{ ucfirst($referral->status) }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
