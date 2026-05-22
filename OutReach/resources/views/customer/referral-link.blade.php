@extends('layouts.app')
@section('title', 'Share Referral - OutReach')
@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <a href="{{ route('customer.dashboard') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-violet-600 mb-4"><i data-lucide="arrow-left" class="w-4 h-4"></i> Back</a>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Share & Earn</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Share your referral link for "{{ $campaign->title }}"</p>
    </div>
    {{-- Campaign Info --}}
    <div class="bg-gradient-to-br from-violet-600 to-indigo-600 rounded-2xl p-6 mb-6 text-white">
        <h2 class="text-xl font-bold mb-2">{{ $campaign->title }}</h2>
        <p class="text-white/80 text-sm mb-4">{{ $campaign->description }}</p>
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/20 backdrop-blur text-sm font-semibold">
            <i data-lucide="gift" class="w-4 h-4"></i> Earn {{ $campaign->reward_value }} {{ ucfirst($campaign->reward_type) }} per referral
        </div>
    </div>
    {{-- Referral Link --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3">Your Referral Link</h3>
        <div class="flex items-center gap-2 mb-4">
            <input type="text" value="{{ $referralUrl }}" readonly id="referral-url-input" class="flex-1 px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white font-mono text-sm">
            <button onclick="navigator.clipboard.writeText(document.getElementById('referral-url-input').value); this.innerHTML='<i data-lucide=\'check\' class=\'w-5 h-5\'></i>'; setTimeout(()=>{this.innerHTML='<i data-lucide=\'copy\' class=\'w-5 h-5\'></i>'; lucide.createIcons();},2000); lucide.createIcons();" class="p-3 rounded-xl text-white bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 shadow-lg transition-all" id="copy-link-btn" title="Copy link"><i data-lucide="copy" class="w-5 h-5"></i></button>
        </div>
        {{-- Social Share Buttons --}}
        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Share on Social Media</h4>
        <div class="flex flex-wrap gap-3">
            <a href="https://wa.me/?text={{ urlencode('Check this out! Join using my referral link: ' . $referralUrl . '?source=whatsapp') }}" target="_blank" class="social-btn whatsapp" id="share-whatsapp">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                WhatsApp
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($referralUrl . '?source=facebook') }}" target="_blank" class="social-btn facebook" id="share-facebook">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                Facebook
            </a>
            <a href="https://twitter.com/intent/tweet?text={{ urlencode('Join using my referral link and earn rewards! ' . $referralUrl . '?source=twitter') }}" target="_blank" class="social-btn twitter" id="share-twitter">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                X / Twitter
            </a>
            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($referralUrl . '?source=linkedin') }}" target="_blank" class="social-btn linkedin" id="share-linkedin">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                LinkedIn
            </a>
            <button onclick="navigator.clipboard.writeText('{{ $referralUrl }}?source=instagram'); this.querySelector('span').textContent='Copied!'; setTimeout(()=>this.querySelector('span').textContent='Instagram (Copy)',2000)" class="social-btn" style="background: linear-gradient(135deg, #833AB4, #FD1D1D, #F77737)" id="share-instagram">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                <span>Instagram (Copy)</span>
            </button>
        </div>
    </div>
    {{-- Write Review --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Leave a Review</h3>
        <form method="POST" action="{{ route('customer.review.submit') }}" id="review-form" x-data="{ rating: 5 }">
            @csrf
            <input type="hidden" name="campaign_id" value="{{ $campaign->_id }}">
            <input type="hidden" name="rating" x-model="rating">
            <div class="flex items-center gap-1 mb-4">
                @for($i = 1; $i <= 5; $i++)
                <button type="button" @click="rating = {{ $i }}" class="focus:outline-none">
                    <i data-lucide="star" class="w-7 h-7 transition-colors" :class="{{ $i }} <= rating ? 'text-amber-400 fill-amber-400' : 'text-gray-300 dark:text-gray-600'"></i>
                </button>
                @endfor
            </div>
            <textarea name="comment" rows="3" required class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 resize-none mb-4" placeholder="Share your experience with this campaign..."></textarea>
            <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-violet-600 to-indigo-600 shadow-lg shadow-violet-500/25 transition-all hover:-translate-y-0.5" id="submit-review-btn">Submit Review</button>
        </form>
    </div>
</div>
@endsection
