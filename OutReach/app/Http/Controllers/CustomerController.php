<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\User;
use App\Models\Referral;
use App\Models\Reward;
use App\Models\Click;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * CustomerController
 *
 * Handles all customer/advocate functionality:
 * - Dashboard with personal stats
 * - Referral link management
 * - View rewards and points
 * - View referred friends
 * - Submit reviews/testimonials
 */
class CustomerController extends Controller
{
    /**
     * Show the customer dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Get all active campaigns the customer can participate in
        $activeCampaigns = Campaign::where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->orderBy('created_at', 'desc')
            ->get();

        // Get referrals made by this customer
        $myReferrals = Referral::where('referrer_id', (string) $user->_id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get rewards earned
        $myRewards = Reward::where('user_id', (string) $user->_id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get referred friends
        $referredFriends = User::where('referred_by', $user->referral_code)->get();

        $totalReferrals = $myReferrals->count();
        $successfulReferrals = $myReferrals->where('status', 'converted')->count();
        $totalPoints = $user->points ?? 0;

        return view('customer.dashboard', compact(
            'user', 'activeCampaigns', 'myReferrals', 'myRewards',
            'referredFriends', 'totalReferrals', 'successfulReferrals', 'totalPoints'
        ));
    }

    /**
     * Show referral link for a specific campaign.
     */
    public function referralLink(string $campaignId)
    {
        $user = Auth::user();
        $campaign = Campaign::findOrFail($campaignId);

        $referralUrl = url("/ref/{$user->referral_code}?campaign={$campaign->_id}");

        return view('customer.referral-link', compact('user', 'campaign', 'referralUrl'));
    }

    /**
     * Submit a review/testimonial for a campaign.
     */
    public function submitReview(Request $request)
    {
        $request->validate([
            'campaign_id' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        Review::create([
            'user_id' => (string) Auth::id(),
            'campaign_id' => $request->campaign_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => true,
        ]);

        return redirect()->back()->with('success', 'Thank you for your review!');
    }

    /**
     * Show all rewards for the customer.
     */
    public function rewards()
    {
        $user = Auth::user();
        $rewards = Reward::where('user_id', (string) $user->_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customer.rewards', compact('user', 'rewards'));
    }
}
