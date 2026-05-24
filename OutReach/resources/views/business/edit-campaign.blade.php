@extends('layouts.app')
@section('title', 'Edit Campaign - OutReach')
@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <a href="{{ route('business.dashboard') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-violet-600 mb-4"><i data-lucide="arrow-left" class="w-4 h-4"></i> Back</a>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Edit Campaign</h1>
    </div>
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 md:p-8">
        @if($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-900/50 text-red-700 dark:text-red-400 text-sm">
            @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
        </div>
        @endif
        <form method="POST" action="{{ route('business.campaign.update', $campaign->_id) }}" id="edit-campaign-form">
            @csrf @method('PUT')
            <div class="space-y-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Title *</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $campaign->title) }}" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description *</label>
                    <textarea name="description" id="description" rows="4" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white resize-none">{{ old('description', $campaign->description) }}</textarea>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="reward_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reward Type *</label>
                        <select name="reward_type" id="reward_type" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white">
                            @foreach(['points','coupon','cashback','badge'] as $type)
                            <option value="{{ $type }}" {{ old('reward_type', $campaign->reward_type)==$type?'selected':'' }}>{{ ucfirst($type) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="reward_value" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reward Value *</label>
                        <input type="number" name="reward_value" id="reward_value" value="{{ old('reward_value', $campaign->reward_value) }}" required min="1" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white">
                    </div>
                </div>
                <div>
                    <label for="reward_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reward Description *</label>
                    <input type="text" name="reward_description" id="reward_description" value="{{ old('reward_description', $campaign->reward_description) }}" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Start Date *</label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date', \Carbon\Carbon::parse($campaign->start_date)->format('Y-m-d')) }}" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white">
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">End Date *</label>
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date', \Carbon\Carbon::parse($campaign->end_date)->format('Y-m-d')) }}" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white">
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status *</label>
                        <select name="status" id="status" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white">
                            @foreach(['active','paused','ended'] as $s)
                            <option value="{{ $s }}" {{ old('status', $campaign->status)==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label for="max_referrals" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Max Referrals (0 = unlimited)</label>
                    <input type="number" name="max_referrals" id="max_referrals" value="{{ old('max_referrals', $campaign->max_referrals) }}" min="0" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white">
                </div>
                <div class="pt-4 border-t border-gray-200 dark:border-gray-800">
                    <button type="submit" id="update-campaign-btn" class="w-full sm:w-auto px-6 py-2.5 rounded-xl font-semibold text-sm text-white bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 shadow-lg shadow-violet-500/25 transition-all hover:-translate-y-0.5">Update Campaign</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
