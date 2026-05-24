{{-- Home/Landing Page - OutReach Platform --}}
@extends('layouts.app')

@section('title', 'OutReach - Social Referral & Customer Outreach Platform')

@section('content')

{{-- Hero Section --}}
<section class="relative overflow-hidden border-b border-gray-150 dark:border-gray-900 bg-gray-50/30 dark:bg-gray-950/10">
    {{-- Dynamic Vercel-style premium dotted grid --}}
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:16px_26px] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)] pointer-events-none"></div>

    {{-- Subtle, highly-blended dark mode ambient glow --}}
    <div class="hero-orb w-80 h-80 bg-violet-500/10 -top-40 -left-40 pointer-events-none z-0" style="position:absolute;border-radius:50%;filter:blur(100px);"></div>
    <div class="hero-orb w-80 h-80 bg-indigo-500/10 -bottom-40 -right-40 pointer-events-none z-0" style="position:absolute;border-radius:50%;filter:blur(100px);"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
        <div class="text-center max-w-3xl mx-auto">
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-violet-100/60 dark:bg-violet-950/40 text-violet-700 dark:text-violet-300 text-xs font-semibold mb-6 animate-fade-in-up border border-violet-200/30 dark:border-violet-800/20">
                <i data-lucide="zap" class="w-3.5 h-3.5"></i>
                Social Media Based P2P Outreach
            </div>

            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight mb-6 animate-fade-in-up stagger-1">
                Grow Your Business
                <span class="block bg-gradient-to-r from-violet-600 via-indigo-600 to-cyan-500 bg-clip-text text-transparent animate-gradient-text">Through Referrals</span>
            </h1>

            <p class="text-sm sm:text-base md:text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto mb-10 animate-fade-in-up stagger-2 leading-relaxed">
                Empower your customers to become brand advocates. Create referral campaigns, share on social media, track outreach, and reward loyalty — all in one unified platform.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 animate-fade-in-up stagger-3">
                @auth
                    @if(Auth::user()->isBusiness())
                        <a href="{{ route('business.dashboard') }}" class="w-full sm:w-auto px-6 py-3 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 shadow-md shadow-violet-500/25 hover:shadow-violet-500/40 transition-all" id="hero-cta-dashboard">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('customer.dashboard') }}" class="w-full sm:w-auto px-6 py-3 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 shadow-md shadow-violet-500/25 hover:shadow-violet-500/40 transition-all" id="hero-cta-dashboard">
                            Go to Dashboard
                        </a>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="w-full sm:w-auto px-6 py-3 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 shadow-md shadow-violet-500/25 hover:shadow-violet-500/40 transition-all" id="hero-cta-register">
                        Start Free Today
                    </a>
                @endauth
                <a href="{{ route('campaigns') }}" class="w-full sm:w-auto px-6 py-3 rounded-xl text-sm font-semibold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:border-violet-300 dark:hover:border-violet-750 shadow-sm hover:shadow transition-all" id="hero-cta-campaigns">
                    View Campaigns
                </a>
            </div>
        </div>

        {{-- Connected Stats Bar - Premium Grid Panel --}}
        <div class="mt-16 max-w-3xl mx-auto bg-white/70 dark:bg-gray-900/60 backdrop-blur-xl border border-gray-200/60 dark:border-gray-850 rounded-xl divide-y sm:divide-y-0 sm:divide-x divide-gray-150 dark:divide-gray-800 grid grid-cols-1 sm:grid-cols-3 shadow-md animate-fade-in-up stagger-4">
            <div class="text-center py-5 px-6 flex flex-col justify-center">
                <div class="text-2xl md:text-3xl font-extrabold text-violet-600 dark:text-violet-400 stat-number">{{ number_format($totalUsers) }}+</div>
                <div class="text-[10px] text-gray-500 dark:text-gray-450 mt-1 font-semibold uppercase tracking-wider">Active Users</div>
            </div>
            <div class="text-center py-5 px-6 flex flex-col justify-center">
                <div class="text-2xl md:text-3xl font-extrabold text-indigo-600 dark:text-indigo-400 stat-number">{{ number_format($totalCampaigns) }}+</div>
                <div class="text-[10px] text-gray-500 dark:text-gray-450 mt-1 font-semibold uppercase tracking-wider">Campaigns</div>
            </div>
            <div class="text-center py-5 px-6 flex flex-col justify-center">
                <div class="text-2xl md:text-3xl font-extrabold text-cyan-600 dark:text-cyan-400 stat-number">{{ number_format($totalReferrals) }}+</div>
                <div class="text-[10px] text-gray-500 dark:text-gray-450 mt-1 font-semibold uppercase tracking-wider">Successful Referrals</div>
            </div>
        </div>
    </div>
</section>

{{-- How It Works Section --}}
<section class="py-16 bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 reveal">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-3">How It Works</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 max-w-xl mx-auto leading-relaxed">Turn your customers into your most powerful marketing channel in three simple steps.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Step 1 --}}
            <div class="relative p-6 rounded-xl bg-gradient-to-br from-violet-50/50 to-indigo-50/30 dark:from-violet-950/20 dark:to-indigo-950/10 border border-violet-100/60 dark:border-violet-900/30 card-hover reveal-left">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-600 to-indigo-600 flex items-center justify-center mb-5 shadow shadow-violet-500/20">
                    <i data-lucide="megaphone" class="w-5 h-5 text-white"></i>
                </div>
                <div class="absolute top-5 right-5 text-3xl font-extrabold text-violet-200/50 dark:text-violet-900/20 select-none">01</div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Create Campaign</h3>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 leading-relaxed">Business owners design creative campaigns with customizable points or code rewards.</p>
            </div>

            {{-- Step 2 --}}
            <div class="relative p-6 rounded-xl bg-gradient-to-br from-indigo-50/50 to-cyan-50/30 dark:from-indigo-950/20 dark:to-cyan-950/10 border border-indigo-100/60 dark:border-indigo-900/30 card-hover reveal">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-600 to-cyan-600 flex items-center justify-center mb-5 shadow shadow-indigo-500/20">
                    <i data-lucide="share-2" class="w-5 h-5 text-white"></i>
                </div>
                <div class="absolute top-5 right-5 text-3xl font-extrabold text-indigo-200/50 dark:text-indigo-900/20 select-none">02</div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Share & Refer</h3>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 leading-relaxed">Advocates get unique, high-conversion links to share instantly via P2P social media channels.</p>
            </div>

            {{-- Step 3 --}}
            <div class="relative p-6 rounded-xl bg-gradient-to-br from-cyan-50/50 to-emerald-50/30 dark:from-cyan-950/20 dark:to-emerald-950/10 border border-cyan-100/60 dark:border-cyan-900/30 card-hover reveal-right">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-600 to-emerald-600 flex items-center justify-center mb-5 shadow shadow-cyan-500/20">
                    <i data-lucide="trophy" class="w-5 h-5 text-white"></i>
                </div>
                <div class="absolute top-5 right-5 text-3xl font-extrabold text-cyan-200/50 dark:text-cyan-900/20 select-none">03</div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Earn Rewards</h3>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 leading-relaxed">As soon as referred friends sign up, both referrers and friends receive automated rewards.</p>
            </div>
        </div>
    </div>
</section>

{{-- Active Campaigns Section --}}
@if($activeCampaigns->count() > 0)
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-10 reveal">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-1.5">Active Campaigns</h2>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">Join a referral campaign and start claiming points.</p>
            </div>
            <a href="{{ route('campaigns') }}" class="hidden sm:inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-950/40 hover:bg-violet-100 dark:hover:bg-violet-900/40 transition-colors border border-violet-200/30 dark:border-violet-850">
                View All <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($activeCampaigns as $campaign)
            <div class="group p-5 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 card-hover reveal">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-500/10 to-indigo-500/10 dark:from-violet-500/20 dark:to-indigo-500/20 flex items-center justify-center border border-violet-100 dark:border-violet-900/30">
                        <i data-lucide="gift" class="w-5 h-5 text-violet-600 dark:text-violet-400"></i>
                    </div>
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100/60 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 border border-emerald-200/30">
                        <span class="status-dot active"></span>
                        Active
                    </span>
                </div>
                <h3 class="text-base font-bold text-gray-900 dark:text-white mb-1.5 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors">{{ $campaign->title }}</h3>
                <p class="text-xs text-gray-600 dark:text-gray-400 mb-4 line-clamp-2 leading-relaxed">{{ $campaign->description }}</p>

                <div class="flex items-center gap-3 mb-4 text-xs">
                    <span class="inline-flex items-center gap-1 text-amber-600 dark:text-amber-400 font-bold bg-amber-50 dark:bg-amber-950/30 px-2 py-0.5 rounded border border-amber-250/20">
                        <i data-lucide="coins" class="w-3.5 h-3.5"></i>
                        {{ $campaign->reward_value }} {{ ucfirst($campaign->reward_type) }}
                    </span>
                    <span class="text-gray-300 dark:text-gray-700">•</span>
                    <span class="text-gray-500 dark:text-gray-400 font-medium">
                        Ends {{ \Carbon\Carbon::parse($campaign->end_date)->diffForHumans() }}
                    </span>
                </div>

                <a href="{{ route('campaign.detail', $campaign->_id) }}" class="inline-flex items-center gap-1.5 w-full justify-center px-4 py-2.5 rounded-xl text-xs font-semibold text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-950/40 hover:bg-violet-100 dark:hover:bg-violet-900/40 transition-colors border border-violet-200/30 dark:border-violet-850 group-hover:bg-gradient-to-r group-hover:from-violet-600 group-hover:to-indigo-600 group-hover:text-white group-hover:border-transparent group-hover:shadow group-hover:shadow-violet-500/10">
                    View Campaign <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Leaderboard Preview --}}
@if($topReferrers->count() > 0)
<section class="py-16 bg-white dark:bg-gray-900 border-t border-b border-gray-100 dark:border-gray-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
            <div class="reveal-left">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 text-xs font-semibold mb-4 border border-amber-200/20">
                    <i data-lucide="award" class="w-3.5 h-3.5"></i> Ranking Standings
                </div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-3">Top Advocates</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-8 leading-relaxed">See who’s leading the P2P advocate boards. Connect campaigns, share with friends, and scale up the ladder!</p>
                <a href="{{ route('leaderboard') }}" class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 shadow shadow-violet-500/25 transition-all" id="home-leaderboard-link">
                    View Full Leaderboard <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>

            <div class="space-y-2.5 reveal-right">
                @foreach($topReferrers as $index => $referrer)
                <div class="flex items-center gap-3 p-3.5 rounded-xl bg-gray-50/70 dark:bg-gray-800/30 border border-gray-150 dark:border-gray-850 card-hover">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-black text-sm shrink-0
                        @if($index === 0) bg-amber-100 dark:bg-amber-950/50 text-amber-600
                        @elseif($index === 1) bg-gray-200 dark:bg-gray-700 text-gray-605 dark:text-gray-300
                        @elseif($index === 2) bg-orange-100 dark:bg-orange-950/50 text-orange-600
                        @else bg-violet-100 dark:bg-violet-950/50 text-violet-600 dark:text-violet-400
                        @endif">
                        {{ $index + 1 }}
                    </div>
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-violet-500 to-indigo-500 flex items-center justify-center text-white font-bold text-xs shrink-0">
                        {{ strtoupper(substr($referrer->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $referrer->name }}</p>
                        <p class="text-[10px] text-gray-450 dark:text-gray-400 font-medium">{{ count($referrer->badges ?? []) }} badges earned</p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-sm font-bold text-violet-600 dark:text-violet-400">{{ number_format($referrer->points) }}</p>
                        <p class="text-[9px] text-gray-400 dark:text-gray-500 uppercase tracking-wider font-semibold">points</p>
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
<section class="py-16 bg-gray-50/10 dark:bg-gray-950/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10 reveal">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-2">What Our Users Say</h2>
            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 max-w-md mx-auto">Real feedback from real advocates.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($recentReviews as $review)
            @php
                $reviewer = \App\Models\User::find($review->user_id);
            @endphp
            <div class="p-5 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 card-hover reveal-scale">
                <div class="flex items-center gap-1 mb-2.5">
                    @for($i = 1; $i <= 5; $i++)
                        <i data-lucide="star" class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'text-amber-400 fill-amber-400' : 'text-gray-200 dark:text-gray-700' }}"></i>
                    @endfor
                </div>
                <p class="text-gray-600 dark:text-gray-450 text-xs leading-relaxed mb-4 italic">"{{ $review->comment }}"</p>
                <div class="flex items-center gap-2.5 pt-2 border-t border-gray-50 dark:border-gray-800/60">
                    <div class="w-6 h-6 rounded-full bg-gradient-to-br from-violet-500 to-indigo-500 flex items-center justify-center text-white text-[10px] font-bold shrink-0">
                        {{ $reviewer ? strtoupper(substr($reviewer->name, 0, 1)) : '?' }}
                    </div>
                    <span class="text-xs font-semibold text-gray-900 dark:text-white truncate">{{ $reviewer ? $reviewer->name : 'Anonymous' }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- CTA Section --}}
<section class="py-16 bg-gradient-to-br from-violet-600 via-indigo-600 to-cyan-650 relative overflow-hidden">
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg%20width%3D%2260%22%20height%3D%2260%22%20viewBox%3D%220%200%2060%2060%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cg%20fill%3D%22none%22%20fill-rule%3D%22evenodd%22%3E%3Cg%20fill%3D%22%23ffffff%22%20fill-opacity%3D%220.04%22%3E%3Cpath%20d%3D%22M36%2034v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6%2034v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6%204V0H4v4H0v2h4v4h2V6h4V4H6z%22/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-40 pointer-events-none"></div>
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center reveal-scale">
        <h2 class="text-2xl md:text-3xl lg:text-4xl font-extrabold text-white mb-4 tracking-tight">Ready to Supercharge Your Outreach?</h2>
        <p class="text-sm text-white/80 mb-8 max-w-xl mx-auto leading-relaxed">Join hundreds of businesses and thousands of advocates already growing together through peer-to-peer referral campaigns.</p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            @auth
                @if(Auth::user()->isBusiness())
                    <a href="{{ route('business.dashboard') }}" class="w-full sm:w-auto px-6 py-3 rounded-xl text-sm font-semibold text-violet-700 bg-white hover:bg-gray-50 transition-colors shadow-md" id="cta-dashboard-biz">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('customer.dashboard') }}" class="w-full sm:w-auto px-6 py-3 rounded-xl text-sm font-semibold text-white border border-white/30 hover:bg-white/10 transition-colors" id="cta-dashboard-cust">
                        Go to Dashboard
                    </a>
                @endif
            @else
                <a href="{{ route('register') }}?role=business" class="w-full sm:w-auto px-6 py-3 rounded-xl text-sm font-semibold text-violet-700 bg-white hover:bg-gray-50 transition-colors shadow-md" id="cta-business">
                    I'm a Business
                </a>
                <a href="{{ route('register') }}?role=customer" class="w-full sm:w-auto px-6 py-3 rounded-xl text-sm font-semibold text-white border border-white/30 hover:bg-white/10 transition-colors" id="cta-customer">
                    I'm a Customer
                </a>
            @endauth
        </div>
    </div>
</section>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.05
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
