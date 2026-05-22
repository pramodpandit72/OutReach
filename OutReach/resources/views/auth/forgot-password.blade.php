@extends('layouts.app')

@section('title', 'Forgot Password - OutReach')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 animate-fade-in-up">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-violet-600 to-indigo-600 flex items-center justify-center mx-auto mb-4 shadow-lg shadow-violet-500/25 animate-float">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Reset Password</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Enter your email and we'll help you recover it</p>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-8 shadow-xl shadow-gray-200/50 dark:shadow-none">
            {{-- Status Messages --}}
            @if(session('status'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-900/50 text-emerald-700 dark:text-emerald-400 text-sm flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-5 h-5 shrink-0 text-emerald-500"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            {{-- Local Testing Helper --}}
            @if(session('debug_link'))
                <div class="mb-6 p-5 rounded-xl bg-violet-50 dark:bg-violet-950/50 border border-violet-200 dark:border-violet-850/50 text-sm">
                    <div class="flex items-center gap-2 text-violet-800 dark:text-violet-300 font-bold mb-2">
                        <i data-lucide="terminal" class="w-4 h-4 text-violet-600 dark:text-violet-400"></i>
                        Local Testing Link
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 mb-3 text-xs">For testing convenience, you can click the button below to immediately open the reset password screen:</p>
                    <a href="{{ session('debug_link') }}" class="inline-flex items-center gap-2 w-full justify-center px-4 py-2.5 rounded-lg text-sm font-semibold text-white bg-violet-600 hover:bg-violet-700 transition-colors shadow-md">
                        <i data-lucide="external-link" class="w-4 h-4"></i> Reset Password Now
                    </a>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-900/50 text-red-700 dark:text-red-400 text-sm">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" id="forgot-password-form">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 transition-all"
                            placeholder="Enter your email address">
                    </div>

                    <button type="submit" class="w-full px-6 py-3.5 rounded-xl text-base font-semibold text-white bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 shadow-lg shadow-violet-500/25 hover:shadow-violet-500/40 transition-all hover:-translate-y-0.5">
                        Send Reset Link
                    </button>
                </div>
            </form>
        </div>

        <p class="text-center mt-6 text-sm text-gray-600 dark:text-gray-400">
            Remember your password?
            <a href="{{ route('login') }}" class="font-semibold text-violet-600 dark:text-violet-400 hover:text-violet-700 dark:hover:text-violet-300" id="forgot-back-to-login">Sign in</a>
        </p>
    </div>
</div>
@endsection
