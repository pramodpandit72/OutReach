@extends('layouts.app')
@section('title', $campaign->title . ' - OutReach')
@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="{{ route('campaigns') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-violet-600 mb-6"><i data-lucide="arrow-left" class="w-4 h-4"></i> All Campaigns</a>
    {{-- Campaign Header --}}
    <div class="bg-gradient-to-br from-violet-600 via-indigo-600 to-cyan-600 rounded-2xl p-8 mb-8 text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg%20width%3D%2260%22%20height%3D%2260%22%20viewBox%3D%220%200%2060%2060%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cg%20fill%3D%22none%22%20fill-rule%3D%22evenodd%22%3E%3Cg%20fill%3D%22%23ffffff%22%20fill-opacity%3D%220.06%22%3E%3Cpath%20d%3D%22M36%2034v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6%2034v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6%204V0H4v4H0v2h4v4h2V6h4V4H6z%22/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-50 pointer-events-none"></div>
        <div class="relative">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/20 backdrop-blur mb-4">
                <span class="status-dot {{ $campaign->status }}"></span> {{ ucfirst($campaign->status) }}
            </span>
            <h1 class="text-3xl md:text-4xl font-bold mb-3">{{ $campaign->title }}</h1>
            <p class="text-white/80 text-lg mb-6">{{ $campaign->description }}</p>
            <div class="flex flex-wrap gap-4">
                <div class="px-4 py-2 rounded-xl bg-white/15 backdrop-blur text-sm font-semibold"><i data-lucide="gift" class="w-4 h-4 inline mr-1"></i>{{ $campaign->reward_value }} {{ ucfirst($campaign->reward_type) }}</div>
                <div class="px-4 py-2 rounded-xl bg-white/15 backdrop-blur text-sm font-semibold"><i data-lucide="calendar" class="w-4 h-4 inline mr-1"></i>{{ \Carbon\Carbon::parse($campaign->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($campaign->end_date)->format('M d, Y') }}</div>
                <div class="px-4 py-2 rounded-xl bg-white/15 backdrop-blur text-sm font-semibold"><i data-lucide="users" class="w-4 h-4 inline mr-1"></i>{{ $totalReferrals }} referrals</div>
            </div>
        </div>
    </div>
    {{-- Stats --}}
    <div class="grid grid-cols-2 gap-4 mb-8">
        <div class="p-5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 text-center">
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalReferrals }}</p>
            <p class="text-sm text-gray-500">Total Referrals</p>
        </div>
        <div class="p-5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 text-center">
            <p class="text-2xl font-bold text-emerald-600">{{ $totalConversions }}</p>
            <p class="text-sm text-gray-500">Conversions</p>
        </div>
    </div>
    {{-- Business Info --}}
    @if($business)
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 mb-8">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-3">About the Business</h2>
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-violet-500 to-indigo-500 flex items-center justify-center text-white font-bold text-lg">{{ strtoupper(substr($business->name, 0, 1)) }}</div>
            <div>
                <p class="font-semibold text-gray-900 dark:text-white">{{ $business->company_name ?? $business->name }}</p>
                @if($business->company_description)<p class="text-sm text-gray-500 dark:text-gray-400">{{ $business->company_description }}</p>@endif
            </div>
        </div>
    </div>
    @endif
    {{-- Join CTA --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 mb-8 text-center">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Want to participate?</h2>
        <p class="text-gray-500 dark:text-gray-400 mb-4">Sign up as a customer and start sharing to earn rewards!</p>
        @auth
            @if(Auth::user()->isCustomer())
            <a href="{{ route('customer.referral', $campaign->_id) }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold text-white bg-gradient-to-r from-violet-600 to-indigo-600 shadow-lg shadow-violet-500/25 transition-all hover:-translate-y-0.5" id="join-campaign-btn"><i data-lucide="share-2" class="w-4 h-4"></i> Get My Referral Link</a>
            @else
            <p class="text-sm text-gray-500">This feature is for customers. Switch to a customer account to participate.</p>
            @endif
        @else
        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold text-white bg-gradient-to-r from-violet-600 to-indigo-600 shadow-lg shadow-violet-500/25 transition-all hover:-translate-y-0.5" id="signup-campaign-btn"><i data-lucide="user-plus" class="w-4 h-4"></i> Sign Up to Join</a>
        @endauth
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
                <p class="text-xs text-gray-500">— {{ $revUser->name ?? 'Anonymous' }}, {{ $review->created_at->diffForHumans() }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
