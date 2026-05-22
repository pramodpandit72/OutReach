@extends('layouts.app')
@section('title', 'Join via Referral - OutReach')
@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-lg">
        {{-- Referrer Info --}}
        <div class="text-center mb-8">
            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-violet-500 to-indigo-500 flex items-center justify-center text-white text-3xl font-black mx-auto mb-4 shadow-xl shadow-violet-500/25 animate-float">
                {{ strtoupper(substr($referrer->name, 0, 1)) }}
            </div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $referrer->name }} invited you!</h1>
            @if($campaign)
            <p class="text-gray-600 dark:text-gray-400">Join <span class="font-semibold text-violet-600 dark:text-violet-400">"{{ $campaign->title }}"</span> and both of you earn rewards!</p>
            @else
            <p class="text-gray-600 dark:text-gray-400">Join OutReach and start earning rewards through referrals!</p>
            @endif
        </div>

        {{-- Reward Info --}}
        @if($campaign)
        <div class="bg-gradient-to-br from-violet-600 to-indigo-600 rounded-2xl p-6 mb-6 text-white text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/20 backdrop-blur text-lg font-bold mb-2">
                <i data-lucide="gift" class="w-5 h-5"></i> {{ $campaign->reward_value }} {{ ucfirst($campaign->reward_type) }}
            </div>
            <p class="text-white/80 text-sm">{{ $campaign->reward_description ?? $campaign->description }}</p>
        </div>
        @endif

        {{-- Sign Up Form --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-8 shadow-xl">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6 text-center">Create Your Account</h2>

            @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-900/50 text-red-700 dark:text-red-400 text-sm">
                @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('register.submit') }}" id="referral-signup-form">
                @csrf
                <input type="hidden" name="referred_by" value="{{ $referralCode }}">
                <input type="hidden" name="campaign_id" value="{{ $campaign ? $campaign->_id : '' }}">
                <input type="hidden" name="role" value="customer">

                <div class="space-y-5">
                    <div>
                        <label for="ref-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name</label>
                        <input type="text" name="name" id="ref-name" value="{{ old('name') }}" required class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400" placeholder="Your name">
                    </div>
                    <div>
                        <label for="ref-email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                        <input type="email" name="email" id="ref-email" value="{{ old('email') }}" required class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400" placeholder="you@example.com">
                    </div>
                    <div>
                        <label for="ref-password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password</label>
                        <input type="password" name="password" id="ref-password" required class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400" placeholder="Min 8 characters">
                    </div>
                    <div>
                        <label for="ref-password-confirm" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="ref-password-confirm" required class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400" placeholder="Re-enter password">
                    </div>
                    <button type="submit" id="referral-signup-btn" class="w-full px-6 py-3.5 rounded-xl font-semibold text-white bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 shadow-lg shadow-violet-500/25 transition-all hover:-translate-y-0.5">
                        Join & Earn Rewards
                    </button>
                </div>
            </form>
        </div>

        <p class="text-center mt-6 text-sm text-gray-600 dark:text-gray-400">
            Already have an account? <a href="{{ route('login') }}" class="font-semibold text-violet-600 dark:text-violet-400">Sign in</a>
        </p>
    </div>
</div>
@endsection
