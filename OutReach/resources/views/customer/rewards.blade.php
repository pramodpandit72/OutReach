@extends('layouts.app')
@section('title', 'My Rewards - OutReach')
@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <a href="{{ route('customer.dashboard') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-violet-600 mb-4"><i data-lucide="arrow-left" class="w-4 h-4"></i> Back</a>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">My Rewards</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Total Points: <span class="font-bold text-violet-600 dark:text-violet-400">{{ number_format($user->points) }}</span></p>
    </div>
    @if($rewards->count() > 0)
    <div class="space-y-4">
        @foreach($rewards as $reward)
        @php $rwCampaign = \App\Models\Campaign::find($reward->campaign_id); @endphp
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 card-hover flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0
                @if($reward->type === 'points') bg-violet-100 dark:bg-violet-950/50
                @elseif($reward->type === 'coupon') bg-emerald-100 dark:bg-emerald-950/50
                @elseif($reward->type === 'cashback') bg-cyan-100 dark:bg-cyan-950/50
                @else bg-amber-100 dark:bg-amber-950/50 @endif">
                @if($reward->type === 'points')<i data-lucide="coins" class="w-6 h-6 text-violet-600 dark:text-violet-400"></i>
                @elseif($reward->type === 'coupon')<i data-lucide="ticket" class="w-6 h-6 text-emerald-600 dark:text-emerald-400"></i>
                @elseif($reward->type === 'cashback')<i data-lucide="banknote" class="w-6 h-6 text-cyan-600 dark:text-cyan-400"></i>
                @else<i data-lucide="award" class="w-6 h-6 text-amber-600 dark:text-amber-400"></i>@endif
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-gray-900 dark:text-white">{{ $reward->description }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $rwCampaign ? $rwCampaign->title : 'Campaign' }} • {{ $reward->created_at->diffForHumans() }}</p>
                @if($reward->type === 'coupon' && $reward->coupon_code)
                    <div class="mt-2 inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 rounded-xl text-xs font-mono font-bold text-emerald-700 dark:text-emerald-400">
                        <i data-lucide="ticket" class="w-3.5 h-3.5 block shrink-0"></i>
                        <span>Code: {{ $reward->coupon_code }}</span>
                    </div>
                @endif
            </div>
            <div class="text-right shrink-0">
                <p class="font-bold text-lg text-violet-600 dark:text-violet-400">+{{ number_format($reward->value) }}</p>
                <p class="text-xs text-gray-500">{{ ucfirst($reward->type) }}</p>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 px-6 py-16 text-center">
        <div class="w-16 h-16 rounded-2xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center mx-auto mb-4"><i data-lucide="gift" class="w-8 h-8 text-gray-400"></i></div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No Rewards Yet</h3>
        <p class="text-gray-500 mb-4">Start sharing referral links to earn rewards!</p>
        <a href="{{ route('customer.dashboard') }}" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-violet-600 to-indigo-600 shadow-lg shadow-violet-500/25">Browse Campaigns</a>
    </div>
    @endif
</div>
@endsection
