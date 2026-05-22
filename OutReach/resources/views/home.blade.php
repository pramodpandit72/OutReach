{{-- Home/Landing Page - OutReach Platform --}}
@extends('layouts.app')

@section('title', 'OutReach - Social Referral & Customer Outreach Platform')

@section('content')

{{-- Hero Section --}}
<section class="relative overflow-hidden">
    {{-- Gradient orbs --}}
    <div class="hero-orb w-96 h-96 bg-violet-500 -top-48 -left-48 pointer-events-none z-0" style="position:absolute;border-radius:50%;filter:blur(80px);opacity:0.18;"></div>
    <div class="hero-orb w-96 h-96 bg-indigo-500 -bottom-48 -right-48 pointer-events-none z-0" style="position:absolute;border-radius:50%;filter:blur(80px);opacity:0.18;"></div>
    <div class="hero-orb w-64 h-64 bg-cyan-400 top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-none z-0" style="position:absolute;border-radius:50%;filter:blur(80px);opacity:0.10;"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-32">
        <div class="text-center max-w-4xl mx-auto">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-violet-100 dark:bg-violet-950/50 text-violet-700 dark:text-violet-300 text-sm font-medium mb-6 animate-fade-in-up">
                <i data-lucide="zap" class="w-4 h-4"></i>
                Social Media Based P2P Outreach
            </div>

            <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black tracking-tight mb-6 animate-fade-in-up stagger-1" style="opacity:0;">
                Grow Your Business
                <span class="block bg-gradient-to-r from-violet-600 via-indigo-600 to-cyan-500 bg-clip-text text-transparent animate-gradient-text">Through Referrals</span>
            </h1>

            <p class="text-lg md:text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto mb-10 animate-fade-in-up stagger-2" style="opacity:0;">
                Empower your customers to become brand advocates. Create referral campaigns, share on social media, track outreach, and reward loyalty — all in one platform.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 animate-fade-in-up stagger-3" style="opacity:0;">
                <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 rounded-2xl text-base font-semibold text-white bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 shadow-xl shadow-violet-500/25 hover:shadow-violet-500/40 transition-all hover:-translate-y-0.5" id="hero-cta-register">
                    Start Free Today
                </a>
                <a href="{{ route('campaigns') }}" class="w-full sm:w-auto px-8 py-4 rounded-2xl text-base font-semibold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:border-violet-300 dark:hover:border-violet-700 shadow-lg hover:shadow-xl transition-all hover:-translate-y-0.5" id="hero-cta-campaigns">
                    View Campaigns
                </a>
            </div>
        </div>

        {{-- Stats Bar --}}
        <div class="mt-20 grid grid-cols-1 sm:grid-cols-3 gap-6 max-w-3xl mx-auto animate-fade-in-up stagger-4" style="opacity:0;">
            <div class="text-center p-6 rounded-2xl glass-card">
                <div class="text-3xl md:text-4xl font-black text-violet-600 dark:text-violet-400 stat-number">{{ number_format($totalUsers) }}+</div>
                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1 font-medium">Active Users</div>
            </div>
            <div class="text-center p-6 rounded-2xl glass-card">
                <div class="text-3xl md:text-4xl font-black text-indigo-600 dark:text-indigo-400 stat-number">{{ number_format($totalCampaigns) }}+</div>
                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1 font-medium">Campaigns</div>
            </div>
            <div class="text-center p-6 rounded-2xl glass-card">
                <div class="text-3xl md:text-4xl font-black text-cyan-600 dark:text-cyan-400 stat-number">{{ number_format($totalReferrals) }}+</div>
                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1 font-medium">Successful Referrals</div>
            </div>
        </div>
    </div>
</section>

{{-- How It Works Section --}}
<section class="py-20 bg-white dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 reveal">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">How It Works</h2>
            <p class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">Turn your customers into your most powerful marketing channel in three simple steps.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- Step 1 --}}
            <div class="relative p-8 rounded-2xl bg-gradient-to-br from-violet-50 to-indigo-50 dark:from-violet-950/30 dark:to-indigo-950/30 border border-violet-100 dark:border-violet-900/50 card-hover reveal-left">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-violet-600 to-indigo-600 flex items-center justify-center mb-6 shadow-lg shadow-violet-500/25">
                    <i data-lucide="megaphone" class="w-7 h-7 text-white"></i>
                </div>
                <div class="absolute top-6 right-6 text-6xl font-black text-violet-200 dark:text-violet-900/50">01</div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Create Campaign</h3>
                <p class="text-gray-600 dark:text-gray-400">Business creates a referral campaign with rewards like "Refer 3 friends and get 20% discount".</p>
            </div>

            {{-- Step 2 --}}
            <div class="relative p-8 rounded-2xl bg-gradient-to-br from-indigo-50 to-cyan-50 dark:from-indigo-950/30 dark:to-cyan-950/30 border border-indigo-100 dark:border-indigo-900/50 card-hover reveal">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-600 to-cyan-600 flex items-center justify-center mb-6 shadow-lg shadow-indigo-500/25">
                    <i data-lucide="share-2" class="w-7 h-7 text-white"></i>
                </div>
                <div class="absolute top-6 right-6 text-6xl font-black text-indigo-200 dark:text-indigo-900/50">02</div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Share & Refer</h3>
                <p class="text-gray-600 dark:text-gray-400">Customers get unique referral links and share them on WhatsApp, Instagram, Facebook, X, and LinkedIn.</p>
            </div>

            {{-- Step 3 --}}
            <div class="relative p-8 rounded-2xl bg-gradient-to-br from-cyan-50 to-emerald-50 dark:from-cyan-950/30 dark:to-emerald-950/30 border border-cyan-100 dark:border-cyan-900/50 card-hover reveal-right">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-cyan-600 to-emerald-600 flex items-center justify-center mb-6 shadow-lg shadow-cyan-500/25">
                    <i data-lucide="trophy" class="w-7 h-7 text-white"></i>
                </div>
                <div class="absolute top-6 right-6 text-6xl font-black text-cyan-200 dark:text-cyan-900/50">03</div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Earn Rewards</h3>
                <p class="text-gray-600 dark:text-gray-400">When friends sign up, both the referrer and new customer earn points, coupons, cashback, and badges.</p>
            </div>
        </div>
    </div>
</section>

{{-- Active Campaigns Section --}}
@if($activeCampaigns->count() > 0)
<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-12 reveal">
            <div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-2">Active Campaigns</h2>
                <p class="text-gray-600 dark:text-gray-400">Join a campaign and start earning rewards today.</p>
            </div>
            <a href="{{ route('campaigns') }}" class="hidden sm:inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-950/50 hover:bg-violet-100 dark:hover:bg-violet-900/50 transition-colors">
                View All <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($activeCampaigns as $campaign)
            <div class="group p-6 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 card-hover reveal">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-violet-500/10 to-indigo-500/10 dark:from-violet-500/20 dark:to-indigo-500/20 flex items-center justify-center">
                        <i data-lucide="gift" class="w-6 h-6 text-violet-600 dark:text-violet-400"></i>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400">
                        <span class="status-dot active"></span>
                        Active
                    </span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors">{{ $campaign->title }}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">{{ $campaign->description }}</p>

                <div class="flex items-center gap-4 mb-4 text-sm">
                    <span class="inline-flex items-center gap-1 text-amber-600 dark:text-amber-400 font-semibold">
                        <i data-lucide="coins" class="w-4 h-4"></i>
                        {{ $campaign->reward_value }} {{ ucfirst($campaign->reward_type) }}
                    </span>
                    <span class="text-gray-400">•</span>
                    <span class="text-gray-500 dark:text-gray-400">
                        Ends {{ \Carbon\Carbon::parse($campaign->end_date)->diffForHumans() }}
                    </span>
                </div>

                <a href="{{ route('campaign.detail', $campaign->_id) }}" class="inline-flex items-center gap-2 w-full justify-center px-4 py-2.5 rounded-xl text-sm font-semibold text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-950/50 hover:bg-violet-100 dark:hover:bg-violet-900/50 transition-colors group-hover:bg-gradient-to-r group-hover:from-violet-600 group-hover:to-indigo-600 group-hover:text-white group-hover:shadow-lg group-hover:shadow-violet-500/25">
                    View Campaign <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Leaderboard Preview --}}
@if($topReferrers->count() > 0)
<section class="py-20 bg-white dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="reveal-left">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">Top Referrers</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-8">See who's leading the outreach game. Join campaigns and climb the leaderboard!</p>
                <a href="{{ route('leaderboard') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 shadow-lg shadow-violet-500/25 hover:shadow-violet-500/40 transition-all" id="home-leaderboard-link">
                    View Full Leaderboard <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>

            <div class="space-y-3 reveal-right">
                @foreach($topReferrers as $index => $referrer)
                <div class="flex items-center gap-4 p-4 rounded-xl bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-800 card-hover">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-black text-lg
                        @if($index === 0) bg-amber-100 dark:bg-amber-950/50 text-amber-600
                        @elseif($index === 1) bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300
                        @elseif($index === 2) bg-orange-100 dark:bg-orange-950/50 text-orange-600
                        @else bg-violet-100 dark:bg-violet-950/50 text-violet-600 dark:text-violet-400
                        @endif">
                        {{ $index + 1 }}
                    </div>
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-violet-500 to-indigo-500 flex items-center justify-center text-white font-bold text-sm">
                        {{ strtoupper(substr($referrer->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-900 dark:text-white truncate">{{ $referrer->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ count($referrer->badges ?? []) }} badges earned</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-violet-600 dark:text-violet-400">{{ number_format($referrer->points) }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">points</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

{{-- Testimonials Section --}}
@if($recentReviews->count() > 0)
<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 reveal">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">What Our Users Say</h2>
            <p class="text-gray-600 dark:text-gray-400">Real feedback from real advocates.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($recentReviews as $review)
            @php
                $reviewer = \App\Models\User::find($review->user_id);
            @endphp
            <div class="p-6 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 card-hover reveal-scale">
                <div class="flex items-center gap-1 mb-3">
                    @for($i = 1; $i <= 5; $i++)
                        <i data-lucide="star" class="w-4 h-4 {{ $i <= $review->rating ? 'text-amber-400 fill-amber-400' : 'text-gray-300 dark:text-gray-600' }}"></i>
                    @endfor
                </div>
                <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-3">"{{ $review->comment }}"</p>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-violet-500 to-indigo-500 flex items-center justify-center text-white text-xs font-bold">
                        {{ $reviewer ? strtoupper(substr($reviewer->name, 0, 1)) : '?' }}
                    </div>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $reviewer ? $reviewer->name : 'Anonymous' }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- CTA Section --}}
<section class="py-20 bg-gradient-to-br from-violet-600 via-indigo-600 to-cyan-600 relative overflow-hidden">
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg%20width%3D%2260%22%20height%3D%2260%22%20viewBox%3D%220%200%2060%2060%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cg%20fill%3D%22none%22%20fill-rule%3D%22evenodd%22%3E%3Cg%20fill%3D%22%23ffffff%22%20fill-opacity%3D%220.05%22%3E%3Cpath%20d%3D%22M36%2034v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6%2034v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6%204V0H4v4H0v2h4v4h2V6h4V4H6z%22/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-30 pointer-events-none"></div>
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center reveal-scale">
        <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-6">Ready to Supercharge Your Outreach?</h2>
        <p class="text-lg text-white/80 mb-10 max-w-2xl mx-auto">Join hundreds of businesses and thousands of advocates already growing through peer-to-peer referrals.</p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('register') }}?role=business" class="w-full sm:w-auto px-8 py-4 rounded-2xl text-base font-semibold text-violet-700 bg-white hover:bg-gray-100 shadow-xl hover:shadow-2xl transition-all hover:-translate-y-0.5" id="cta-business">
                I'm a Business
            </a>
            <a href="{{ route('register') }}?role=customer" class="w-full sm:w-auto px-8 py-4 rounded-2xl text-base font-semibold text-white border-2 border-white/30 hover:bg-white/10 transition-all hover:-translate-y-0.5" id="cta-customer">
                I'm a Customer
            </a>
        </div>
    </div>
</section>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        const revealElements = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale');
        revealElements.forEach(el => observer.observe(el));
    });
</script>
@endsection

@endsection
