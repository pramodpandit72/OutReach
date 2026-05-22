{{-- Register Page --}}
@extends('layouts.app')

@section('title', 'Register - OutReach')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-violet-600 to-indigo-600 flex items-center justify-center mx-auto mb-4 shadow-lg shadow-violet-500/25">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create Your Account</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Join OutReach and start growing</p>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-8 shadow-xl shadow-gray-200/50 dark:shadow-none">
            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-900/50 text-red-700 dark:text-red-400 text-sm">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if(!isset($referralCode) || !$referralCode)
                {{-- Google OAuth Sign Up Button (not shown for referral signups) --}}
                <a href="{{ route('auth.google') }}" id="google-register-btn"
                   class="w-full flex items-center justify-center gap-3 px-6 py-3.5 rounded-xl text-base font-semibold border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-750 hover:border-gray-300 dark:hover:border-gray-600 shadow-sm hover:shadow-md transition-all hover:-translate-y-0.5">
                    {{-- Google "G" Logo SVG --}}
                    <svg width="20" height="20" viewBox="0 0 48 48">
                        <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                        <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                        <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                        <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                    </svg>
                    Sign up with Google
                </a>

                {{-- OR Divider --}}
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200 dark:border-gray-700"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white dark:bg-gray-900 text-gray-500 dark:text-gray-400 font-medium">or register with email</span>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('register.submit') }}" id="register-form" x-data="{ role: '{{ old('role', request()->query('role', 'customer')) }}' }">
                @csrf

                @if(isset($referralCode) && $referralCode)
                    <input type="hidden" name="referred_by" value="{{ $referralCode }}">
                    <input type="hidden" name="campaign_id" value="{{ $campaignId }}">
                    <input type="hidden" name="role" value="customer">
                    <div class="mb-6 p-4 rounded-xl bg-violet-50 dark:bg-violet-950/30 border border-violet-200 dark:border-violet-900/50 text-violet-700 dark:text-violet-300 text-sm flex items-center gap-2">
                        <i data-lucide="link" class="w-4 h-4 shrink-0"></i>
                        You were referred! Sign up to receive your reward.
                    </div>
                @else
                    {{-- Role Selection --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">I am a...</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label @click="role = 'business'" :class="role === 'business' ? 'border-violet-500 bg-violet-50 dark:bg-violet-950/30 ring-2 ring-violet-500/20' : 'border-gray-200 dark:border-gray-700 hover:border-violet-300'" class="flex flex-col items-center gap-2 p-4 rounded-xl border cursor-pointer transition-all">
                                <input type="radio" name="role" value="business" x-model="role" class="sr-only">
                                <i data-lucide="building-2" class="w-6 h-6" :class="role === 'business' ? 'text-violet-600 dark:text-violet-400' : 'text-gray-400'"></i>
                                <span class="text-sm font-semibold" :class="role === 'business' ? 'text-violet-700 dark:text-violet-300' : 'text-gray-600 dark:text-gray-400'">Business</span>
                            </label>
                            <label @click="role = 'customer'" :class="role === 'customer' ? 'border-violet-500 bg-violet-50 dark:bg-violet-950/30 ring-2 ring-violet-500/20' : 'border-gray-200 dark:border-gray-700 hover:border-violet-300'" class="flex flex-col items-center gap-2 p-4 rounded-xl border cursor-pointer transition-all">
                                <input type="radio" name="role" value="customer" x-model="role" class="sr-only">
                                <i data-lucide="user" class="w-6 h-6" :class="role === 'customer' ? 'text-violet-600 dark:text-violet-400' : 'text-gray-400'"></i>
                                <span class="text-sm font-semibold" :class="role === 'customer' ? 'text-violet-700 dark:text-violet-300' : 'text-gray-600 dark:text-gray-400'">Customer</span>
                            </label>
                        </div>
                    </div>
                @endif

                <div class="space-y-5">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 transition-all"
                            placeholder="Enter Full Name">
                    </div>

                    <div>
                        <label for="reg-email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email Address</label>
                        <input type="email" name="email" id="reg-email" value="{{ old('email') }}" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 transition-all"
                            placeholder="Enter Email Address">
                    </div>

                    {{-- Business Fields --}}
                    <template x-if="role === 'business'">
                        <div class="space-y-5">
                            <div>
                                <label for="company_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Company Name</label>
                                <input type="text" name="company_name" id="company_name" value="{{ old('company_name') }}"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 transition-all"
                                    placeholder="Your Business Name">
                            </div>
                            <div>
                                <label for="company_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Company Description</label>
                                <textarea name="company_description" id="company_description" rows="2"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 transition-all resize-none"
                                    placeholder="Brief description of your business">{{ old('company_description') }}</textarea>
                            </div>
                        </div>
                    </template>

                    <div>
                        <label for="reg-password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password</label>
                        <input type="password" name="password" id="reg-password" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 transition-all"
                            placeholder="Minimum 8 characters">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 transition-all"
                            placeholder="Re-enter your password">
                    </div>

                    <button type="submit" id="register-submit-btn" class="w-full px-6 py-3.5 rounded-xl text-base font-semibold text-white bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 shadow-lg shadow-violet-500/25 hover:shadow-violet-500/40 transition-all hover:-translate-y-0.5">
                        Create Account
                    </button>
                </div>
            </form>
        </div>

        <p class="text-center mt-6 text-sm text-gray-600 dark:text-gray-400">
            Already have an account?
            <a href="{{ route('login') }}" class="font-semibold text-violet-600 dark:text-violet-400 hover:text-violet-700 dark:hover:text-violet-300" id="register-login-link">Sign in</a>
        </p>
    </div>
</div>
@endsection
