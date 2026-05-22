@extends('layouts.app')

@section('title', 'Edit Profile - OutReach')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 animate-fade-in-up">
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Profile Settings</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Manage your account details, preferences, and password.</p>
    </div>

    @if($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-900/50 text-red-700 dark:text-red-400 text-sm max-w-4xl">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left Card: Account Overview --}}
        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm">
                <div class="flex flex-col items-center text-center">
                    {{-- Avatar --}}
                    @if($user->avatar)
                        <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-full object-cover ring-4 ring-violet-500/20 mb-4 shadow-md">
                    @else
                        <div class="w-24 h-24 rounded-full bg-gradient-to-br from-violet-500 to-indigo-500 flex items-center justify-center text-white text-3xl font-black mb-4 shadow-md shadow-violet-500/20">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif

                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $user->email }}</p>
                    
                    {{-- Role Badge --}}
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold mt-3
                        @if($user->isBusiness()) bg-violet-100 dark:bg-violet-950/50 text-violet-700 dark:text-violet-300
                        @else bg-indigo-100 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-300
                        @endif">
                        <i data-lucide="{{ $user->isBusiness() ? 'building-2' : 'user' }}" class="w-3.5 h-3.5"></i>
                        {{ $user->isBusiness() ? 'Business Account' : 'Customer Account' }}
                    </span>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-800 space-y-4">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500 dark:text-gray-400">Referral Code:</span>
                        <span class="font-mono font-bold text-violet-600 dark:text-violet-400">{{ $user->referral_code }}</span>
                    </div>
                    @if(!$user->isBusiness())
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Total Points:</span>
                            <span class="font-bold text-amber-600 dark:text-amber-400">{{ number_format($user->points) }} pts</span>
                        </div>
                    @endif
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500 dark:text-gray-400">Member Since:</span>
                        <span class="text-gray-900 dark:text-white font-medium">{{ $user->created_at ? $user->created_at->format('M Y') : 'Unknown' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Side: Profile Settings Form --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-8 shadow-sm">
                <form method="POST" action="{{ route('profile.update') }}" class="space-y-8">
                    @csrf

                    {{-- General Information --}}
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-3 mb-5">General Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 transition-all"
                                    placeholder="Full Name">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email Address</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 transition-all"
                                    placeholder="Email Address">
                            </div>
                        </div>
                    </div>

                    {{-- Business-Specific Details --}}
                    @if($user->isBusiness())
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-3 mb-5">Business Details</h3>
                            <div class="space-y-6">
                                <div>
                                    <label for="company_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Company Name</label>
                                    <input type="text" name="company_name" id="company_name" value="{{ old('company_name', $user->company_name) }}"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 transition-all"
                                        placeholder="Company Name">
                                </div>
                                <div>
                                    <label for="company_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Company Description</label>
                                    <textarea name="company_description" id="company_description" rows="4"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 transition-all resize-none"
                                        placeholder="Brief description of your business">{{ old('company_description', $user->company_description) }}</textarea>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Security & Password --}}
                    <div x-data="{ changePassword: false }">
                        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-3 mb-5">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Security & Password</h3>
                            <button type="button" @click="changePassword = !changePassword" 
                                class="text-sm font-semibold text-violet-600 dark:text-violet-400 hover:text-violet-700 dark:hover:text-violet-300"
                                x-text="changePassword ? 'Cancel Change' : 'Change Password'">
                                Change Password
                            </button>
                        </div>

                        <div x-show="changePassword" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                            @if(!empty($user->getAttributes()['password'] ?? null))
                                <div>
                                    <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Current Password</label>
                                    <input type="password" name="current_password" id="current_password"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 transition-all"
                                        placeholder="Enter current password" :required="changePassword">
                                </div>
                            @else
                                <div class="p-4 rounded-xl bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900/50 text-amber-800 dark:text-amber-300 text-sm flex items-center gap-2">
                                    <i data-lucide="info" class="w-4 h-4 shrink-0"></i>
                                    You created this account using Google Sign-In. You can set a password here to enable email/password sign-in.
                                </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New Password</label>
                                    <input type="password" name="password" id="password"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 transition-all"
                                        placeholder="Minimum 8 characters" :required="changePassword">
                                </div>
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 transition-all"
                                        placeholder="Confirm new password" :required="changePassword">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Form Actions --}}
                    <div class="pt-6 border-t border-gray-200 dark:border-gray-800 flex justify-end gap-4">
                        @if($user->isBusiness())
                            <a href="{{ route('business.dashboard') }}" class="px-6 py-3 rounded-xl text-sm font-semibold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition-all">Cancel</a>
                        @else
                            <a href="{{ route('customer.dashboard') }}" class="px-6 py-3 rounded-xl text-sm font-semibold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition-all">Cancel</a>
                        @endif
                        <button type="submit" class="px-8 py-3 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 shadow-lg shadow-violet-500/25 hover:shadow-violet-500/40 transition-all hover:-translate-y-0.5">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
