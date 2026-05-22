<?php
 
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Campaign;
use App\Models\Referral;
use App\Models\Reward;
use App\Models\Click;
use Illuminate\Support\Str;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /**
     * Test customer registration.
     */
    public function test_customer_registration(): void
    {
        $email = 'customer_' . Str::random(8) . '@test.com';
        $response = $this->post('/register', [
            'name' => 'Test Customer',
            'email' => $email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'customer',
        ]);

        $response->assertRedirect('/customer/dashboard');
        $this->assertDatabaseHas('users', [
            'email' => $email,
            'role' => 'customer',
        ], 'mongodb');
    }

    /**
     * Test business registration.
     */
    public function test_business_registration(): void
    {
        $email = 'business_' . Str::random(8) . '@test.com';
        $response = $this->post('/register', [
            'name' => 'Test Business',
            'email' => $email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'business',
            'company_name' => 'Test Company',
            'company_description' => 'Test Description',
        ]);

        $response->assertRedirect('/business/dashboard');
        $this->assertDatabaseHas('users', [
            'email' => $email,
            'role' => 'business',
            'company_name' => 'Test Company',
        ], 'mongodb');
    }

    /**
     * Test login.
     */
    public function test_login(): void
    {
        $email = 'login_' . Str::random(8) . '@test.com';
        $user = User::create([
            'name' => 'Login User',
            'email' => $email,
            'password' => 'password123',
            'role' => 'customer',
        ]);

        $response = $this->post('/login', [
            'email' => $email,
            'password' => 'password123',
        ]);

        $response->assertRedirect('/customer/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test campaign creation by business.
     */
    public function test_campaign_creation(): void
    {
        $business = User::create([
            'name' => 'Camp Business',
            'email' => 'camp_' . Str::random(8) . '@test.com',
            'password' => 'password123',
            'role' => 'business',
            'company_name' => 'Camp Co',
        ]);

        $response = $this->actingAs($business)
            ->post('/business/campaign', [
                'title' => 'Test Campaign',
                'description' => 'Detailed campaign description',
                'reward_type' => 'points',
                'reward_value' => 50,
                'reward_description' => '50 points',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addDays(10)->toDateString(),
                'max_referrals' => 5,
            ]);

        $response->assertRedirect('/business/dashboard');
        $this->assertDatabaseHas('campaigns', [
            'business_id' => (string) $business->_id,
            'title' => 'Test Campaign',
            'reward_value' => 50,
        ], 'mongodb');
    }

    /**
     * Test referral flow.
     */
    public function test_referral_flow(): void
    {
        // 1. Create a business and customer
        $business = User::create([
            'name' => 'Referral Business',
            'email' => 'ref_biz_' . Str::random(8) . '@test.com',
            'password' => 'password123',
            'role' => 'business',
        ]);

        $campaign = Campaign::create([
            'business_id' => (string) $business->_id,
            'title' => 'Referral Campaign',
            'description' => 'Earn points',
            'reward_type' => 'points',
            'reward_value' => 100,
            'reward_description' => '100 points',
            'start_date' => now()->subDays(1),
            'end_date' => now()->addDays(5),
            'status' => 'active',
            'max_referrals' => 0,
        ]);

        $referrer = User::create([
            'name' => 'Referrer Customer',
            'email' => 'referrer_' . Str::random(8) . '@test.com',
            'password' => 'password123',
            'role' => 'customer',
        ]);

        // 2. Simulate referral link click
        $referralCode = $referrer->referral_code;
        $clickResponse = $this->get("/ref/{$referralCode}?campaign={$campaign->_id}&source=whatsapp");
        $clickResponse->assertStatus(200);

        // Check click is tracked in DB
        $this->assertDatabaseHas('clicks', [
            'campaign_id' => (string) $campaign->_id,
            'referrer_id' => (string) $referrer->_id,
            'source' => 'whatsapp',
        ], 'mongodb');

        // 3. Register a new user using referral info
        $referredEmail = 'referred_' . Str::random(8) . '@test.com';
        $registerResponse = $this->post('/register', [
            'name' => 'Referred User',
            'email' => $referredEmail,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'customer',
            'referred_by' => $referralCode,
            'campaign_id' => (string) $campaign->_id,
        ]);

        // Should redirect to customer dashboard
        $registerResponse->assertRedirect('/customer/dashboard');

        // Verify the referred user was created with role 'new_customer'
        $referredUser = User::where('email', $referredEmail)->first();
        $this->assertNotNull($referredUser);
        $this->assertEquals('new_customer', $referredUser->role);

        // Verify referral record is created
        $this->assertDatabaseHas('referrals', [
            'referrer_id' => (string) $referrer->_id,
            'referred_id' => (string) $referredUser->_id,
            'campaign_id' => (string) $campaign->_id,
            'status' => 'converted',
        ], 'mongodb');

        // Verify reward record is created
        $this->assertDatabaseHas('rewards', [
            'user_id' => (string) $referrer->_id,
            'campaign_id' => (string) $campaign->_id,
            'type' => 'points',
            'value' => 100,
        ], 'mongodb');

        // Verify referrer points were incremented
        $referrer->refresh();
        $this->assertEquals(100, $referrer->points);
    }

    /**
     * Test that users cannot access dashboards of other roles.
     */
    public function test_unauthorized_dashboard_access(): void
    {
        $customer = User::create([
            'name' => 'Block Customer',
            'email' => 'block_cust_' . Str::random(8) . '@test.com',
            'password' => 'password123',
            'role' => 'customer',
        ]);

        $business = User::create([
            'name' => 'Block Business',
            'email' => 'block_biz_' . Str::random(8) . '@test.com',
            'password' => 'password123',
            'role' => 'business',
        ]);

        // Customer trying to access business dashboard -> redirects to home with error
        $response = $this->actingAs($customer)->get('/business/dashboard');
        $response->assertRedirect('/');

        // Business trying to access customer dashboard -> redirects to home with error
        $response2 = $this->actingAs($business)->get('/customer/dashboard');
        $response2->assertRedirect('/');
    }

    /**
     * Test campaign CRUD ownership/authorization.
     */
    public function test_campaign_crud_authorization(): void
    {
        $business1 = User::create([
            'name' => 'Biz One',
            'email' => 'biz1_' . Str::random(8) . '@test.com',
            'password' => 'password123',
            'role' => 'business',
        ]);

        $business2 = User::create([
            'name' => 'Biz Two',
            'email' => 'biz2_' . Str::random(8) . '@test.com',
            'password' => 'password123',
            'role' => 'business',
        ]);

        // Business 1 creates a campaign
        $campaign = Campaign::create([
            'business_id' => (string) $business1->_id,
            'title' => 'Biz 1 Campaign',
            'description' => 'Original description',
            'reward_type' => 'points',
            'reward_value' => 50,
            'reward_description' => '50 points',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'status' => 'active',
        ]);

        // Business 2 tries to edit Business 1's campaign -> redirects to dashboard with error
        $responseEdit = $this->actingAs($business2)->get("/business/campaign/{$campaign->_id}/edit");
        $responseEdit->assertRedirect('/business/dashboard');

        // Business 2 tries to update Business 1's campaign -> redirects with error
        $responseUpdate = $this->actingAs($business2)->put("/business/campaign/{$campaign->_id}", [
            'title' => 'Hijacked Campaign',
            'description' => 'Hijacked description',
            'reward_type' => 'points',
            'reward_value' => 50,
            'reward_description' => '50 points',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'status' => 'active',
        ]);
        $responseUpdate->assertRedirect('/business/dashboard');

        // Verify the campaign was NOT changed
        $campaign->refresh();
        $this->assertEquals('Biz 1 Campaign', $campaign->title);

        // Business 2 tries to delete Business 1's campaign -> redirects with error
        $responseDelete = $this->actingAs($business2)->delete("/business/campaign/{$campaign->_id}");
        $responseDelete->assertRedirect('/business/dashboard');

        // Verify the campaign still exists in DB
        $this->assertDatabaseHas('campaigns', [
            '_id' => $campaign->_id,
        ], 'mongodb');
    }

    /**
     * Test customer review submission.
     */
    public function test_submit_campaign_review(): void
    {
        $customer = User::create([
            'name' => 'Reviewer Customer',
            'email' => 'rev_cust_' . Str::random(8) . '@test.com',
            'password' => 'password123',
            'role' => 'customer',
        ]);

        $campaign = Campaign::create([
            'business_id' => 'some_biz_id',
            'title' => 'Reviewable Campaign',
            'description' => 'Describe me',
            'reward_type' => 'points',
            'reward_value' => 10,
            'reward_description' => '10 points',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'status' => 'active',
        ]);

        $response = $this->actingAs($customer)
            ->from("/campaign/{$campaign->_id}")
            ->post('/customer/review', [
                'campaign_id' => (string) $campaign->_id,
                'rating' => 4,
                'comment' => 'Great experience with this referral campaign!',
            ]);

        $response->assertRedirect("/campaign/{$campaign->_id}");
        $this->assertDatabaseHas('reviews', [
            'user_id' => (string) $customer->_id,
            'campaign_id' => (string) $campaign->_id,
            'rating' => 4,
            'comment' => 'Great experience with this referral campaign!',
        ], 'mongodb');
    }

    /**
     * Test max referrals enforcement.
     */
    public function test_campaign_max_referrals_enforcement(): void
    {
        // Create business, customer/referrer, and a campaign with max_referrals = 1
        $business = User::create([
            'name' => 'Max Ref Biz',
            'email' => 'max_ref_biz_' . Str::random(8) . '@test.com',
            'password' => 'password123',
            'role' => 'business',
        ]);

        $campaign = Campaign::create([
            'business_id' => (string) $business->_id,
            'title' => 'Max Ref Campaign',
            'description' => 'Refer one only',
            'reward_type' => 'points',
            'reward_value' => 100,
            'reward_description' => '100 points',
            'start_date' => now()->subDays(1),
            'end_date' => now()->addDays(5),
            'status' => 'active',
            'max_referrals' => 1,
        ]);

        $referrer = User::create([
            'name' => 'Active Referrer',
            'email' => 'referrer_max_' . Str::random(8) . '@test.com',
            'password' => 'password123',
            'role' => 'customer',
        ]);

        // Referral 1: Register referred user 1
        $referredEmail1 = 'referred_one_' . Str::random(8) . '@test.com';
        $registerResponse1 = $this->post('/register', [
            'name' => 'Referred One',
            'email' => $referredEmail1,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'customer',
            'referred_by' => $referrer->referral_code,
            'campaign_id' => (string) $campaign->_id,
        ]);
        $registerResponse1->assertRedirect('/customer/dashboard');

        // Referrer should get 100 points
        $referrer->refresh();
        $this->assertEquals(100, $referrer->points);

        // Logout referred user 1
        $this->post('/logout');

        // Referral 2: Register referred user 2 (exceeding max_referrals = 1)
        $referredEmail2 = 'referred_two_' . Str::random(8) . '@test.com';
        $registerResponse2 = $this->post('/register', [
            'name' => 'Referred Two',
            'email' => $referredEmail2,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'customer',
            'referred_by' => $referrer->referral_code,
            'campaign_id' => (string) $campaign->_id,
        ]);
        $registerResponse2->assertRedirect('/customer/dashboard');

        // Referrer points should STILL be 100 (no new reward processed due to max_referrals limit)
        $referrer->refresh();
        $this->assertEquals(100, $referrer->points);
    }
}

