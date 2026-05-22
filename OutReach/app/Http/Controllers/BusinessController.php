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
 * BusinessController
 *
 * Handles all business/admin functionality:
 * - Dashboard with analytics overview
 * - Campaign CRUD operations
 * - Analytics and reporting
 */
class BusinessController extends Controller
{
    /**
     * Show the business dashboard with overview stats.
     */
    public function dashboard()
    {
        $user = Auth::user();
        $campaigns = Campaign::where('business_id', (string) $user->_id)
            ->orderBy('created_at', 'desc')
            ->get();

        $campaignIds = $campaigns->pluck('_id')->map(fn($id) => (string) $id)->toArray();

        $totalClicks = Click::whereIn('campaign_id', $campaignIds)->count();
        $totalReferrals = Referral::whereIn('campaign_id', $campaignIds)->count();
        $totalConversions = Referral::whereIn('campaign_id', $campaignIds)
            ->where('status', 'converted')->count();
        $totalRewards = Reward::whereIn('campaign_id', $campaignIds)->count();

        // Get recent referrals with user data
        $recentReferrals = Referral::whereIn('campaign_id', $campaignIds)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Top referrers for this business's campaigns
        $topReferrers = Referral::raw(function ($collection) use ($campaignIds) {
            return $collection->aggregate([
                ['$match' => ['campaign_id' => ['$in' => $campaignIds], 'status' => 'converted']],
                ['$group' => ['_id' => '$referrer_id', 'count' => ['$sum' => 1]]],
                ['$sort' => ['count' => -1]],
                ['$limit' => 5],
            ]);
        });

        return view('business.dashboard', compact(
            'user', 'campaigns', 'totalClicks', 'totalReferrals',
            'totalConversions', 'totalRewards', 'recentReferrals', 'topReferrers'
        ));
    }

    /**
     * Show the campaign creation form.
     */
    public function createCampaign()
    {
        return view('business.create-campaign');
    }

    /**
     * Store a new campaign.
     */
    public function storeCampaign(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'reward_type' => 'required|in:points,coupon,cashback,badge',
            'reward_value' => 'required|numeric|min:1',
            'reward_description' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'max_referrals' => 'nullable|integer|min:0',
        ]);

        Campaign::create([
            'business_id' => (string) Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'reward_type' => $request->reward_type,
            'reward_value' => $request->reward_value,
            'reward_description' => $request->reward_description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'active',
            'max_referrals' => $request->max_referrals ?? 0,
        ]);

        return redirect()->route('business.dashboard')->with('success', 'Campaign created successfully!');
    }

    /**
     * Show campaign edit form.
     */
    public function editCampaign(string $id)
    {
        $campaign = Campaign::findOrFail($id);

        // Ensure business owns this campaign
        if ((string) $campaign->business_id !== (string) Auth::id()) {
            return redirect()->route('business.dashboard')->with('error', 'Unauthorized.');
        }

        return view('business.edit-campaign', compact('campaign'));
    }

    /**
     * Update a campaign.
     */
    public function updateCampaign(Request $request, string $id)
    {
        $campaign = Campaign::findOrFail($id);

        if ((string) $campaign->business_id !== (string) Auth::id()) {
            return redirect()->route('business.dashboard')->with('error', 'Unauthorized.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'reward_type' => 'required|in:points,coupon,cashback,badge',
            'reward_value' => 'required|numeric|min:1',
            'reward_description' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:active,paused,ended',
            'max_referrals' => 'nullable|integer|min:0',
        ]);

        $campaign->update($request->only([
            'title', 'description', 'reward_type', 'reward_value',
            'reward_description', 'start_date', 'end_date', 'status', 'max_referrals'
        ]));

        return redirect()->route('business.dashboard')->with('success', 'Campaign updated successfully!');
    }

    /**
     * Delete a campaign.
     */
    public function deleteCampaign(string $id)
    {
        $campaign = Campaign::findOrFail($id);

        if ((string) $campaign->business_id !== (string) Auth::id()) {
            return redirect()->route('business.dashboard')->with('error', 'Unauthorized.');
        }

        $campaign->delete();

        return redirect()->route('business.dashboard')->with('success', 'Campaign deleted successfully!');
    }

    /**
     * Show detailed analytics for a specific campaign.
     */
    public function campaignAnalytics(string $id)
    {
        $campaign = Campaign::findOrFail($id);

        if ((string) $campaign->business_id !== (string) Auth::id()) {
            return redirect()->route('business.dashboard')->with('error', 'Unauthorized.');
        }

        $campaignId = (string) $campaign->_id;

        $totalClicks = Click::where('campaign_id', $campaignId)->count();
        $totalReferrals = Referral::where('campaign_id', $campaignId)->count();
        $totalConversions = Referral::where('campaign_id', $campaignId)
            ->where('status', 'converted')->count();
        $totalRewards = Reward::where('campaign_id', $campaignId)->count();

        // Source breakdown
        $sourceBreakdown = Click::raw(function ($collection) use ($campaignId) {
            return $collection->aggregate([
                ['$match' => ['campaign_id' => $campaignId]],
                ['$group' => ['_id' => '$source', 'count' => ['$sum' => 1]]],
                ['$sort' => ['count' => -1]],
            ]);
        });

        // Recent referrals
        $referrals = Referral::where('campaign_id', $campaignId)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        // Reviews
        $reviews = Review::where('campaign_id', $campaignId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('business.campaign-analytics', compact(
            'campaign', 'totalClicks', 'totalReferrals', 'totalConversions',
            'totalRewards', 'sourceBreakdown', 'referrals', 'reviews'
        ));
    }

    /**
     * Show overall analytics page for all campaigns.
     */
    public function analytics()
    {
        $user = Auth::user();
        $campaigns = Campaign::where('business_id', (string) $user->_id)->get();
        $campaignIds = $campaigns->pluck('_id')->map(fn($id) => (string) $id)->toArray();

        $totalClicks = Click::whereIn('campaign_id', $campaignIds)->count();
        $totalReferrals = Referral::whereIn('campaign_id', $campaignIds)->count();
        $totalConversions = Referral::whereIn('campaign_id', $campaignIds)
            ->where('status', 'converted')->count();
        $totalRewards = Reward::whereIn('campaign_id', $campaignIds)->count();

        // Campaign performance summary
        $campaignStats = $campaigns->map(function ($campaign) {
            $cid = (string) $campaign->_id;
            return [
                'campaign' => $campaign,
                'clicks' => Click::where('campaign_id', $cid)->count(),
                'referrals' => Referral::where('campaign_id', $cid)->count(),
                'conversions' => Referral::where('campaign_id', $cid)->where('status', 'converted')->count(),
            ];
        });

        return view('business.analytics', compact(
            'campaigns', 'totalClicks', 'totalReferrals',
            'totalConversions', 'totalRewards', 'campaignStats'
        ));
    }
}
