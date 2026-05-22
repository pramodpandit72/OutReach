<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ForgotPasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| OutReach - Social Referral & Customer Outreach Platform
|
| Route groups:
| 1. Public routes - Home, campaigns, leaderboard, reviews
| 2. Auth routes - Login, register, logout
| 3. Business routes - Dashboard, campaign CRUD, analytics
| 4. Customer routes - Dashboard, referral links, rewards
| 5. Referral handler - Processes referral link clicks
|
*/

// ============================================
// Public Routes
// ============================================
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/campaigns', [PageController::class, 'campaigns'])->name('campaigns');
Route::get('/campaign/{id}', [PageController::class, 'campaignDetail'])->name('campaign.detail');
Route::get('/leaderboard', [PageController::class, 'leaderboard'])->name('leaderboard');
Route::get('/reviews', [PageController::class, 'reviews'])->name('reviews');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');

// ============================================
// Referral Link Handler
// ============================================
Route::get('/ref/{referralCode}', [PageController::class, 'handleReferral'])->name('referral.handle');

// ============================================
// Authentication Routes
// ============================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

    // Google OAuth Routes
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

    // Password Reset Routes
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// ============================================
// Business/Admin Routes
// ============================================
Route::middleware(['auth', 'role:business'])->prefix('business')->name('business.')->group(function () {
    Route::get('/dashboard', [BusinessController::class, 'dashboard'])->name('dashboard');
    Route::get('/campaign/create', [BusinessController::class, 'createCampaign'])->name('campaign.create');
    Route::post('/campaign', [BusinessController::class, 'storeCampaign'])->name('campaign.store');
    Route::get('/campaign/{id}/edit', [BusinessController::class, 'editCampaign'])->name('campaign.edit');
    Route::put('/campaign/{id}', [BusinessController::class, 'updateCampaign'])->name('campaign.update');
    Route::delete('/campaign/{id}', [BusinessController::class, 'deleteCampaign'])->name('campaign.delete');
    Route::get('/campaign/{id}/analytics', [BusinessController::class, 'campaignAnalytics'])->name('campaign.analytics');
    Route::get('/analytics', [BusinessController::class, 'analytics'])->name('analytics');
});

// ============================================
// Customer/Advocate Routes
// ============================================
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');
    Route::get('/referral/{campaignId}', [CustomerController::class, 'referralLink'])->name('referral');
    Route::post('/review', [CustomerController::class, 'submitReview'])->name('review.submit');
    Route::get('/rewards', [CustomerController::class, 'rewards'])->name('rewards');
});

// ============================================
// Telegram Bot Webhook Route
// ============================================
Route::post('/telegram/webhook', [\App\Http\Controllers\TelegramController::class, 'handleWebhook'])->name('telegram.webhook');
Route::post('/bot/chat', [\App\Http\Controllers\TelegramController::class, 'handleWebChat'])->name('bot.chat');
