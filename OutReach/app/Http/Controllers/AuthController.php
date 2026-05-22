<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Campaign;
use App\Models\Referral;
use App\Models\Reward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;

/**
 * AuthController
 *
 * Handles user registration, login, logout, and Google OAuth.
 * Supports role-based registration (business / customer).
 * Also handles referral signup via referral links.
 */
class AuthController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegister(Request $request)
    {
        $referralCode = $request->query('ref');
        $campaignId = $request->query('campaign');
        return view('auth.register', compact('referralCode', 'campaignId'));
    }

    /**
     * Handle user registration.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:business,customer',
            'company_name' => 'nullable|string|max:255',
            'company_description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $role = $request->role;

        // If registering via referral link, set role to 'new_customer'
        if ($request->filled('referred_by')) {
            $role = 'new_customer';
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => $role,
            'referred_by' => $request->referred_by ?: null,
            'company_name' => $request->company_name,
            'company_description' => $request->company_description,
            'points' => 0,
            'badges' => [],
        ]);

        // If referred, create a referral record and process rewards
        $this->processReferral($user, $request->referred_by, $request->campaign_id);

        Auth::login($user);

        if ($user->isBusiness()) {
            return redirect()->route('business.dashboard')->with('success', 'Welcome to OutReach!');
        }

        return redirect()->route('customer.dashboard')->with('success', 'Welcome to OutReach!');
    }

    /**
     * Show the login form.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle user login.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check if this is a Google-only account (no password set)
        $user = User::where('email', $request->email)->first();
        if ($user && !empty($user->google_id) && empty($user->getAttributes()['password'] ?? null)) {
            return redirect()->back()
                ->withErrors(['email' => 'This account uses Google Sign-In. Please click "Sign in with Google" to continue.'])
                ->withInput();
        }

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->isBusiness()) {
                return redirect()->route('business.dashboard');
            }

            return redirect()->route('customer.dashboard');
        }

        return redirect()->back()
            ->withErrors(['email' => 'The provided credentials do not match our records.'])
            ->withInput();
    }

    /**
     * Redirect the user to Google's OAuth consent screen.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the callback from Google OAuth.
     *
     * If a user with this email already exists, log them in.
     * If they're new, create a customer account with their Google profile info.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Google authentication failed. Please try again.']);
        }

        // Check if user already exists with this email
        $existingUser = User::where('email', $googleUser->getEmail())->first();

        if ($existingUser) {
            // Update Google ID and avatar if not already set
            $updates = [];
            if (empty($existingUser->google_id)) {
                $updates['google_id'] = $googleUser->getId();
            }
            if (empty($existingUser->avatar) && $googleUser->getAvatar()) {
                $updates['avatar'] = $googleUser->getAvatar();
            }
            if (!empty($updates)) {
                $existingUser->update($updates);
            }

            Auth::login($existingUser, true);

            if ($existingUser->isBusiness()) {
                return redirect()->route('business.dashboard')->with('success', 'Welcome back!');
            }
            return redirect()->route('customer.dashboard')->with('success', 'Welcome back!');
        }

        // Create a new user with Google profile info (default role: customer)
        $newUser = User::create([
            'name' => $googleUser->getName(),
            'email' => $googleUser->getEmail(),
            'google_id' => $googleUser->getId(),
            'avatar' => $googleUser->getAvatar(),
            'role' => 'customer',
            'points' => 0,
            'badges' => [],
        ]);

        Auth::login($newUser, true);

        return redirect()->route('customer.dashboard')->with('success', 'Welcome to OutReach! Your account has been created.');
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been logged out.');
    }

    /**
     * Process referral signup — create referral record and award rewards.
     * Enforces max_referrals limit on the campaign.
     *
     * @param User $user The newly registered user
     * @param string|null $referralCode The referral code from the referrer
     * @param string|null $campaignId The campaign ID
     */
    private function processReferral(User $user, ?string $referralCode, ?string $campaignId): void
    {
        if (empty($referralCode) || empty($campaignId)) {
            return;
        }

        $referrer = User::where('referral_code', $referralCode)->first();
        if (!$referrer) {
            return;
        }

        $campaign = Campaign::find($campaignId);
        if (!$campaign) {
            return;
        }

        // Enforce max_referrals limit
        if ($campaign->max_referrals) {
            $currentReferralCount = Referral::where('campaign_id', (string) $campaign->_id)
                ->where('status', 'converted')
                ->count();

            if ($currentReferralCount >= (int) $campaign->max_referrals) {
                // Campaign has reached its referral limit — skip reward but allow signup
                return;
            }
        }

        // Create referral record
        Referral::create([
            'referrer_id' => (string) $referrer->_id,
            'referred_id' => (string) $user->_id,
            'campaign_id' => (string) $campaign->_id,
            'status' => 'converted',
            'clicked_at' => now(),
            'converted_at' => now(),
        ]);

        $couponCode = null;
        if ($campaign->reward_type === 'coupon') {
            $couponCode = 'REF-' . strtoupper(\Illuminate\Support\Str::slug($referrer->name)) . '-' . strtoupper(\Illuminate\Support\Str::random(6));
        }

        // Award reward to referrer
        Reward::create([
            'user_id' => (string) $referrer->_id,
            'campaign_id' => (string) $campaign->_id,
            'type' => $campaign->reward_type,
            'value' => $campaign->reward_value,
            'description' => "Earned for referring {$user->name} via '{$campaign->title}'",
            'status' => 'claimed',
            'coupon_code' => $couponCode,
        ]);

        // Add points to referrer
        $referrer->increment('points', (int) $campaign->reward_value);

        // Check for badge awards
        $totalReferrals = $referrer->referralsMade()->where('status', 'converted')->count();
        $badges = $referrer->badges ?? [];

        if ($totalReferrals >= 5 && !in_array('Rising Star', $badges)) {
            $badges[] = 'Rising Star';
        }
        if ($totalReferrals >= 10 && !in_array('Super Advocate', $badges)) {
            $badges[] = 'Super Advocate';
        }
        if ($totalReferrals >= 25 && !in_array('Outreach Legend', $badges)) {
            $badges[] = 'Outreach Legend';
        }

        $referrer->update(['badges' => $badges]);

        // If referrer has linked telegram, notify them!
        if (!empty($referrer->telegram_chat_id)) {
            try {
                $botToken = env('TELEGRAM_BOT_TOKEN');
                if ($botToken) {
                    $rewardText = "";
                    if ($campaign->reward_type === 'points') {
                        $rewardText = "{$campaign->reward_value} points";
                    } elseif ($campaign->reward_type === 'coupon') {
                        $rewardText = "a discount coupon worth {$campaign->reward_value}";
                    } else {
                        $rewardText = "{$campaign->reward_value}";
                    }
                    
                    $msg = "🎉 *Congratulations!* Your friend *{$user->name}* registered using your referral link for campaign *'{$campaign->title}'*.\n\n🏆 You earned *{$rewardText}*!\n\nKeep sharing to climb the leaderboard!";
                    
                    \Illuminate\Support\Facades\Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                        'chat_id' => $referrer->telegram_chat_id,
                        'text' => $msg,
                        'parse_mode' => 'Markdown',
                    ]);
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to send Telegram notification: " . $e->getMessage());
            }
        }
    }
}
