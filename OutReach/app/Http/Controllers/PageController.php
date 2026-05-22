<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\User;
use App\Models\Referral;
use App\Models\Click;
use App\Models\Review;
use Illuminate\Http\Request;

/**
 * PageController
 *
 * Handles public pages:
 * - Home/Landing page
 * - Campaign detail page
 * - Referral link handler
 * - Leaderboard
 * - Reviews/Testimonials page
 */
class PageController extends Controller
{
    /**
     * Show the home/landing page.
     */
    public function home()
    {
        $activeCampaigns = Campaign::where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Get top referrers for the leaderboard preview
        $topReferrers = User::where('points', '>', 0)
            ->orderBy('points', 'desc')
            ->limit(5)
            ->get();

        // Get recent testimonials
        $recentReviews = Review::where('is_approved', true)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Platform stats
        $totalUsers = User::count();
        $totalCampaigns = Campaign::count();
        $totalReferrals = Referral::where('status', 'converted')->count();

        return view('home', compact(
            'activeCampaigns', 'topReferrers', 'recentReviews',
            'totalUsers', 'totalCampaigns', 'totalReferrals'
        ));
    }

    /**
     * Show campaign detail page.
     */
    public function campaignDetail(string $id)
    {
        $campaign = Campaign::findOrFail($id);
        $business = User::find($campaign->business_id);

        $reviews = Review::where('campaign_id', (string) $campaign->_id)
            ->where('is_approved', true)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalReferrals = Referral::where('campaign_id', (string) $campaign->_id)->count();
        $totalConversions = Referral::where('campaign_id', (string) $campaign->_id)
            ->where('status', 'converted')->count();

        return view('campaign-detail', compact(
            'campaign', 'business', 'reviews', 'totalReferrals', 'totalConversions'
        ));
    }

    /**
     * Handle referral link click.
     * Records the click and redirects to referral signup page.
     */
    public function handleReferral(string $referralCode, Request $request)
    {
        $referrer = User::where('referral_code', $referralCode)->first();

        if (!$referrer) {
            return redirect()->route('home')->with('error', 'Invalid referral link.');
        }

        $campaignId = $request->query('campaign');
        $source = $request->query('source', 'direct');
        $campaign = null;

        if ($campaignId) {
            $campaign = Campaign::find($campaignId);
        }

        // Track the click
        Click::create([
            'campaign_id' => $campaignId ?: 'general',
            'referrer_id' => (string) $referrer->_id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'source' => $source,
        ]);

        // Show the referral signup page
        return view('referral-signup', compact('referrer', 'campaign', 'referralCode'));
    }

    /**
     * Show the full leaderboard page.
     */
    public function leaderboard()
    {
        $topReferrers = User::where('points', '>', 0)
            ->orderBy('points', 'desc')
            ->limit(50)
            ->get();

        // Add rank and referral counts
        $leaderboard = $topReferrers->map(function ($user, $index) {
            $user->rank = $index + 1;
            $user->total_referrals = Referral::where('referrer_id', (string) $user->_id)
                ->where('status', 'converted')->count();
            return $user;
        });

        return view('leaderboard', compact('leaderboard'));
    }

    /**
     * Show the reviews/testimonials page.
     */
    public function reviews()
    {
        $reviews = Review::where('is_approved', true)
            ->orderBy('created_at', 'desc')
            ->get();

        // Load user and campaign data
        $reviews = $reviews->map(function ($review) {
            $review->reviewer = User::find($review->user_id);
            $review->campaignData = Campaign::find($review->campaign_id);
            return $review;
        });

        return view('reviews', compact('reviews'));
    }

    /**
     * Show all campaigns page.
     */
    public function campaigns()
    {
        $campaigns = Campaign::where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->orderBy('created_at', 'desc')
            ->get();

        // Load business data
        $campaigns = $campaigns->map(function ($campaign) {
            $campaign->businessUser = User::find($campaign->business_id);
            return $campaign;
        });

        return view('campaigns', compact('campaigns'));
    }

    /**
     * Show the Privacy Policy page.
     */
    public function privacy()
    {
        return view('privacy');
    }
}
