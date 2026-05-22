@extends('layouts.app')
@section('title', 'Campaign Analytics - OutReach')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <a href="{{ route('business.dashboard') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-violet-600 mb-4"><i data-lucide="arrow-left" class="w-4 h-4"></i> Back</a>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">{{ $campaign->title }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Campaign analytics & performance details.</p>
    </div>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="p-6 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 card-hover">
            <div class="w-10 h-10 rounded-xl bg-violet-100 dark:bg-violet-950/50 flex items-center justify-center mb-3"><i data-lucide="mouse-pointer-click" class="w-5 h-5 text-violet-600"></i></div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalClicks) }}</p>
            <p class="text-sm text-gray-500">Clicks</p>
        </div>
        <div class="p-6 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 card-hover">
            <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-950/50 flex items-center justify-center mb-3"><i data-lucide="users" class="w-5 h-5 text-indigo-600"></i></div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalReferrals) }}</p>
            <p class="text-sm text-gray-500">Referrals</p>
        </div>
        <div class="p-6 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 card-hover">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-950/50 flex items-center justify-center mb-3"><i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i></div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalConversions) }}</p>
            <p class="text-sm text-gray-500">Conversions</p>
        </div>
        <div class="p-6 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 card-hover">
            <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-950/50 flex items-center justify-center mb-3"><i data-lucide="gift" class="w-5 h-5 text-amber-600"></i></div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalRewards) }}</p>
            <p class="text-sm text-gray-500">Rewards</p>
        </div>
    </div>
    {{-- Source Breakdown --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Traffic Sources</h2>
            @php $sources = collect($sourceBreakdown)->toArray(); @endphp
            @if(count($sources) > 0)
            <div class="space-y-3">
                @php $maxCount = collect($sources)->max('count') ?: 1; @endphp
                @foreach($sources as $src)
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-medium text-gray-700 dark:text-gray-300">{{ ucfirst($src['_id'] ?? 'Direct') }}</span>
                        <span class="text-gray-500">{{ $src['count'] }}</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-gradient-to-r from-violet-600 to-indigo-600 h-2 rounded-full" style="width: {{ ($src['count']/$maxCount)*100 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-gray-500 text-sm">No traffic data yet.</p>
            @endif
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Campaign Details</h2>
            <dl class="space-y-3">
                <div class="flex justify-between"><dt class="text-sm text-gray-500">Reward</dt><dd class="text-sm font-semibold text-amber-600">{{ $campaign->reward_value }} {{ ucfirst($campaign->reward_type) }}</dd></div>
                <div class="flex justify-between"><dt class="text-sm text-gray-500">Status</dt><dd><span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold {{ $campaign->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}"><span class="status-dot {{ $campaign->status }}"></span>{{ ucfirst($campaign->status) }}</span></dd></div>
                <div class="flex justify-between"><dt class="text-sm text-gray-500">Start</dt><dd class="text-sm font-medium text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($campaign->start_date)->format('M d, Y') }}</dd></div>
                <div class="flex justify-between"><dt class="text-sm text-gray-500">End</dt><dd class="text-sm font-medium text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($campaign->end_date)->format('M d, Y') }}</dd></div>
                <div class="flex justify-between"><dt class="text-sm text-gray-500">Conv. Rate</dt><dd class="text-sm font-bold text-violet-600">{{ $totalClicks > 0 ? round(($totalConversions/$totalClicks)*100,1) : 0 }}%</dd></div>
            </dl>
        </div>
    </div>
    {{-- Recent Referrals --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800"><h2 class="text-lg font-bold text-gray-900 dark:text-white">Referrals</h2></div>
        @if($referrals->count() > 0)
        <div class="divide-y divide-gray-100 dark:divide-gray-800">
            @foreach($referrals as $ref)
            @php $rUser = \App\Models\User::find($ref->referrer_id); $rdUser = \App\Models\User::find($ref->referred_id); @endphp
            <div class="px-6 py-4 flex items-center gap-4">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-violet-500 to-indigo-500 flex items-center justify-center text-white text-sm font-bold shrink-0">{{ $rUser ? strtoupper(substr($rUser->name,0,1)) : '?' }}</div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-gray-900 dark:text-white"><span class="font-semibold">{{ $rUser->name ?? 'N/A' }}</span> → <span class="font-semibold">{{ $rdUser->name ?? 'N/A' }}</span></p>
                    <p class="text-xs text-gray-500">{{ $ref->created_at->diffForHumans() }}</p>
                </div>
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $ref->status === 'converted' ? 'bg-emerald-100 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400' : 'bg-amber-100 text-amber-700' }}">{{ ucfirst($ref->status) }}</span>
            </div>
            @endforeach
        </div>
        @else
        <div class="px-6 py-12 text-center text-gray-500">No referrals yet.</div>
        @endif
    </div>
    {{-- Reviews --}}
    @if($reviews->count() > 0)
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800"><h2 class="text-lg font-bold text-gray-900 dark:text-white">Reviews</h2></div>
        <div class="divide-y divide-gray-100 dark:divide-gray-800">
            @foreach($reviews as $review)
            @php $revUser = \App\Models\User::find($review->user_id); @endphp
            <div class="px-6 py-4">
                <div class="flex items-center gap-1 mb-2">@for($i=1;$i<=5;$i++)<i data-lucide="star" class="w-4 h-4 {{ $i<=$review->rating ? 'text-amber-400 fill-amber-400' : 'text-gray-300' }}"></i>@endfor</div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">"{{ $review->comment }}"</p>
                <p class="text-xs font-medium text-gray-500">— {{ $revUser->name ?? 'Anonymous' }}, {{ $review->created_at->diffForHumans() }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
