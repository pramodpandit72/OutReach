<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Campaign;
use App\Models\Referral;
use App\Models\Reward;
use App\Models\Review;
use App\Models\Click;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * DatabaseSeeder
 *
 * Seeds demo data for OutReach platform:
 * - 2 business users, 8 customer users
 * - 5 campaigns
 * - Referrals, rewards, clicks, reviews
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        User::truncate();
        Campaign::truncate();
        Referral::truncate();
        Reward::truncate();
        Review::truncate();
        Click::truncate();

        // === Business Users ===
        $biz1 = User::create([
            'name' => 'TechCorp Admin',
            'email' => 'business@outreach.com',
            'password' => 'password123',
            'role' => 'business',
            'company_name' => 'TechCorp Solutions',
            'company_description' => 'Leading technology solutions provider helping businesses grow digitally.',
            'points' => 0, 'badges' => [],
        ]);

        $biz2 = User::create([
            'name' => 'FoodHub Manager',
            'email' => 'foodhub@outreach.com',
            'password' => 'password123',
            'role' => 'business',
            'company_name' => 'FoodHub Delivery',
            'company_description' => 'Fast and fresh food delivery to your doorstep.',
            'points' => 0, 'badges' => [],
        ]);

        // === Customer Users ===
        $customers = [];
        $customerData = [
            ['Rahul Sharma', 'rahul@test.com', 1250, ['Rising Star', 'Super Advocate']],
            ['Priya Patel', 'priya@test.com', 980, ['Rising Star']],
            ['Aman Singh', 'aman@test.com', 750, ['Rising Star']],
            ['Sneha Gupta', 'sneha@test.com', 520, []],
            ['Vikram Reddy', 'vikram@test.com', 340, []],
            ['Ananya Joshi', 'ananya@test.com', 200, []],
            ['Karan Mehta', 'karan@test.com', 150, []],
            ['Divya Nair', 'divya@test.com', 80, []],
        ];

        foreach ($customerData as $cd) {
            $customers[] = User::create([
                'name' => $cd[0], 'email' => $cd[1],
                'password' => 'password123',
                'role' => 'customer', 'points' => $cd[2], 'badges' => $cd[3],
            ]);
        }

        // === Campaigns ===
        $campaigns = [];

        $campaigns[] = Campaign::create([
            'business_id' => (string) $biz1->_id,
            'title' => 'Refer 3 Friends & Get 20% Discount',
            'description' => 'Share your love for TechCorp! Refer your friends and earn 100 points for each signup. Get a 20% discount coupon when you reach 3 referrals.',
            'reward_type' => 'points', 'reward_value' => 100,
            'reward_description' => 'Earn 100 points for each successful referral',
            'start_date' => now()->subDays(10), 'end_date' => now()->addDays(50),
            'status' => 'active', 'max_referrals' => 0,
        ]);

        $campaigns[] = Campaign::create([
            'business_id' => (string) $biz1->_id,
            'title' => 'Summer Tech Sale Referral',
            'description' => 'Summer is here! Share our deals with friends and earn cashback on every successful purchase referral.',
            'reward_type' => 'cashback', 'reward_value' => 50,
            'reward_description' => '₹50 cashback per referral who makes a purchase',
            'start_date' => now()->subDays(5), 'end_date' => now()->addDays(40),
            'status' => 'active', 'max_referrals' => 10,
        ]);

        $campaigns[] = Campaign::create([
            'business_id' => (string) $biz2->_id,
            'title' => 'FoodHub Buddy Program',
            'description' => 'Invite your friends to FoodHub! They get free delivery on their first order, and you earn loyalty points.',
            'reward_type' => 'points', 'reward_value' => 75,
            'reward_description' => '75 loyalty points per friend who orders',
            'start_date' => now()->subDays(15), 'end_date' => now()->addDays(30),
            'status' => 'active', 'max_referrals' => 0,
        ]);

        $campaigns[] = Campaign::create([
            'business_id' => (string) $biz2->_id,
            'title' => 'Weekend Feast Coupon Drive',
            'description' => 'Share our weekend deals and earn exclusive coupons. Each referral unlocks a discount coupon for both of you!',
            'reward_type' => 'coupon', 'reward_value' => 25,
            'reward_description' => '25% off coupon for you and your friend',
            'start_date' => now()->subDays(3), 'end_date' => now()->addDays(60),
            'status' => 'active', 'max_referrals' => 5,
        ]);

        $campaigns[] = Campaign::create([
            'business_id' => (string) $biz1->_id,
            'title' => 'Early Bird Badge Challenge',
            'description' => 'Be among the first 50 referrers to earn an exclusive Early Bird badge and 200 bonus points!',
            'reward_type' => 'badge', 'reward_value' => 200,
            'reward_description' => 'Exclusive Early Bird badge + 200 bonus points',
            'start_date' => now(), 'end_date' => now()->addDays(90),
            'status' => 'active', 'max_referrals' => 0,
        ]);

        // === Referrals, Rewards, Clicks ===
        $sources = ['whatsapp', 'facebook', 'twitter', 'linkedin', 'instagram', 'direct'];
        $reviewComments = [
            'Amazing platform! Earned so many points just by sharing links with friends.',
            'Love how easy it is to share and track referrals. The rewards are great!',
            'My friends and I both got discounts. Win-win! Highly recommend.',
            'The leaderboard feature keeps me motivated. Already in top 5!',
            'Simple, clean, and effective. Best referral platform I have used.',
            'Great rewards system. I have earned cashback and coupons just by sharing.',
            'The social sharing buttons make it super easy to share on WhatsApp and Instagram.',
            'Businesses get real visibility through this. Very professional setup.',
        ];

        // Create referrals between customers
        $refPairs = [[0,3],[0,4],[0,5],[0,6],[0,7],[1,4],[1,5],[1,6],[2,5],[2,6],[2,7],[3,7]];
        foreach ($refPairs as $idx => $pair) {
            $campIdx = $idx % count($campaigns);
            $campaign = $campaigns[$campIdx];

            Referral::create([
                'referrer_id' => (string) $customers[$pair[0]]->_id,
                'referred_id' => (string) $customers[$pair[1]]->_id,
                'campaign_id' => (string) $campaign->_id,
                'status' => 'converted',
                'clicked_at' => now()->subDays(rand(1, 20)),
                'converted_at' => now()->subDays(rand(0, 10)),
            ]);

            $couponCode = null;
            if ($campaign->reward_type === 'coupon') {
                $couponCode = 'REF-' . strtoupper(\Illuminate\Support\Str::slug($customers[$pair[0]]->name)) . '-' . strtoupper(\Illuminate\Support\Str::random(6));
            }

            Reward::create([
                'user_id' => (string) $customers[$pair[0]]->_id,
                'campaign_id' => (string) $campaign->_id,
                'type' => $campaign->reward_type,
                'value' => $campaign->reward_value,
                'description' => "Earned for referring {$customers[$pair[1]]->name}",
                'status' => 'claimed',
                'coupon_code' => $couponCode,
            ]);
        }

        // Create clicks
        for ($i = 0; $i < 80; $i++) {
            Click::create([
                'campaign_id' => (string) $campaigns[array_rand($campaigns)]->_id,
                'referrer_id' => (string) $customers[array_rand($customers)]->_id,
                'ip_address' => '192.168.1.' . rand(1, 254),
                'user_agent' => 'Mozilla/5.0',
                'source' => $sources[array_rand($sources)],
            ]);
        }

        // Create reviews
        foreach ($customers as $idx => $customer) {
            if ($idx < count($reviewComments)) {
                Review::create([
                    'user_id' => (string) $customer->_id,
                    'campaign_id' => (string) $campaigns[$idx % count($campaigns)]->_id,
                    'rating' => rand(4, 5),
                    'comment' => $reviewComments[$idx],
                    'is_approved' => true,
                ]);
            }
        }

        // Set referred_by for some customers
        $customers[3]->update(['referred_by' => $customers[0]->referral_code]);
        $customers[4]->update(['referred_by' => $customers[0]->referral_code]);
        $customers[5]->update(['referred_by' => $customers[1]->referral_code]);

        echo "✅ Seeded: 2 businesses, 8 customers, 5 campaigns, " . count($refPairs) . " referrals, 80 clicks, " . min(count($customers), count($reviewComments)) . " reviews\n";
    }
}
