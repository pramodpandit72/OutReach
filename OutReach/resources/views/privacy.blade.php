@extends('layouts.app')

@section('title', 'Privacy Policy - OutReach')

@section('content')
<div class="relative min-h-[80vh] overflow-hidden py-16 sm:py-24">
    <!-- Background Hero Orbs -->
    <div class="hero-orb bg-gradient-to-tr from-violet-500 to-indigo-500 w-[500px] h-[500px] -top-40 -left-40 opacity-20 dark:opacity-10"></div>
    <div class="hero-orb bg-gradient-to-tr from-sky-500 to-emerald-500 w-[400px] h-[400px] -bottom-20 -right-20 opacity-20 dark:opacity-10"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 reveal active">
        <!-- Breadcrumb / Tag -->
        <div class="flex justify-center mb-6">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-violet-50 text-violet-700 dark:bg-violet-950/50 dark:text-violet-400 border border-violet-100 dark:border-violet-900/50">
                <i data-lucide="shield-check" class="w-4 h-4"></i>
                Security & Trust
            </span>
        </div>

        <!-- Page Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight text-gray-900 dark:text-white mb-4">
                Privacy <span class="bg-gradient-to-r from-violet-600 to-indigo-600 bg-clip-text text-transparent">Policy</span>
            </h1>
            <p class="text-base text-gray-500 dark:text-gray-400 max-w-xl mx-auto">
                Last updated: May 22, 2026. Your privacy and the security of your referral networks are our absolute top priorities.
            </p>
        </div>

        <!-- Glassmorphic Policy Content -->
        <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl border border-gray-150 dark:border-gray-800 rounded-3xl p-8 sm:p-12 shadow-xl shadow-gray-200/20 dark:shadow-none space-y-10">
            
            <!-- Section 1 -->
            <section class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-violet-50 dark:bg-violet-950/50 flex items-center justify-center text-violet-600 dark:text-violet-400">
                        <i data-lucide="database" class="w-5 h-5"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">1. Information We Collect</h2>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed pl-13">
                    OutReach is a peer-to-peer customer referral platform. To facilitate dynamic referrals, clicks tracking, and reward points distribution, we collect the following datasets:
                </p>
                <ul class="list-disc pl-18 space-y-2 text-sm text-gray-600 dark:text-gray-400">
                    <li><strong class="text-gray-900 dark:text-white">Account Details:</strong> Name, email address, password, profile avatar, and role selection (Business vs. Customer).</li>
                    <li><strong class="text-gray-900 dark:text-white">OAuth Data:</strong> If registering via Google, we collect Google IDs and avatars to deliver seamless, secure OAuth single-sign-on.</li>
                    <li><strong class="text-gray-900 dark:text-white">Outreach & Referral Data:</strong> Campaign creation records, referral click-logs (IP addresses, User Agents, and sharing sources such as WhatsApp or Facebook), conversion timestamps, and points or coupon rewards.</li>
                </ul>
            </section>

            <hr class="border-gray-100 dark:border-gray-800" />

            <!-- Section 2 -->
            <section class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-sky-50 dark:bg-sky-950/50 flex items-center justify-center text-sky-600 dark:text-sky-400">
                        <i data-lucide="eye" class="w-5 h-5"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">2. How We Use Your Data</h2>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed pl-13">
                    We process your information to deliver a robust, fair, and high-performance referral ecosystem:
                </p>
                <ul class="list-disc pl-18 space-y-2 text-sm text-gray-600 dark:text-gray-400">
                    <li>To generate unique referral URLs and track peer-to-peer shares on social media channels.</li>
                    <li>To verify successful conversions and automatically credit reward points or coupons to advocates.</li>
                    <li>To show high-performing advocates on the public Leaderboard.</li>
                    <li>To send secure account verification, forgot password reset links, and push instant notifications via our integrated Telegram Bot.</li>
                </ul>
            </section>

            <hr class="border-gray-100 dark:border-gray-800" />

            <!-- Section 3 -->
            <section class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <i data-lucide="lock" class="w-5 h-5"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">3. Data Security & Storage</h2>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed pl-13">
                    We employ industry-leading standards to store and safeguard your data:
                </p>
                <ul class="list-disc pl-18 space-y-2 text-sm text-gray-600 dark:text-gray-400">
                    <li>All sensitive user passwords are encrypted using state-of-the-art bcrypt hashing before they are committed to MongoDB.</li>
                    <li>Our databases are hosted on secured MongoDB Atlas clusters protected by firewalls and encryption-at-rest.</li>
                    <li>Communication across our servers, OAuth providers, and the Telegram API is entirely handled over secured HTTPS (TLS) connections.</li>
                </ul>
            </section>

            <hr class="border-gray-100 dark:border-gray-800" />

            <!-- Section 4 -->
            <section class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/50 flex items-center justify-center text-amber-600 dark:text-amber-400">
                        <i data-lucide="cookie" class="w-5 h-5"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">4. Cookies & Session Management</h2>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed pl-13">
                    OutReach uses light, modern sessions to authenticate actions. Cookies are strictly utilized to manage secure Laravel sessions and retain user preferences (such as light vs. dark mode toggle). No cross-site ad tracking cookies are ever loaded on our platform.
                </p>
            </section>

            <hr class="border-gray-100 dark:border-gray-800" />

            <!-- Section 5 -->
            <section class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-950/50 flex items-center justify-center text-rose-600 dark:text-rose-400">
                        <i data-lucide="help-circle" class="w-5 h-5"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">5. Contact Support</h2>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed pl-13">
                    If you have questions regarding this Privacy Policy, your registered data, or would like to request data deletion, please contact us or connect with our AI Marketing assistant via our integrated Telegram bot on the bottom-right of your screen!
                </p>
            </section>

        </div>
    </div>
</div>
@endsection
