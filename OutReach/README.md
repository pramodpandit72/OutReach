# OutReach

OutReach is a Laravel-based social referral and customer outreach platform. It helps businesses create referral campaigns, lets customers share referral links, tracks referral clicks and conversions, and rewards customers when their referrals successfully join through a campaign.

The website is built for two main user groups:

- Business users who create and manage referral campaigns.
- Customer users who participate in campaigns, share referral links, earn rewards, and appear on the leaderboard.

## What This Website Does

OutReach works like a referral marketing platform. A business can publish campaigns such as "Refer 3 Friends & Get 20% Discount" or "FoodHub Buddy Program". Customers can join those campaigns and generate unique referral links. When another person opens a referral link, the website records the click, shows a referral signup page, and connects the new signup to the original referrer.

After a successful referred signup, the system creates a referral record, gives the referrer a reward, adds points to the referrer's account, and may award badges based on referral milestones.

The platform also provides public pages where visitors can browse active campaigns, view campaign details, see customer reviews, and check the referral leaderboard.

## Main Features

- Public landing page with active campaigns, platform stats, recent reviews, and top referrers.
- Campaign listing page for browsing active referral campaigns.
- Campaign detail page with campaign description, rewards, referral stats, and reviews.
- User registration and login.
- Google OAuth login support through Laravel Socialite.
- Custom forgot-password and reset-password flow.
- Role-based access for business and customer users.
- Business dashboard with campaign and referral performance metrics.
- Campaign creation, editing, deletion, and status management.
- Campaign analytics with clicks, referrals, conversions, rewards, and traffic source breakdown.
- Customer dashboard with active campaigns, personal referral stats, points, rewards, and referred friends.
- Unique referral link generation for each customer and campaign.
- Referral click tracking with campaign, referrer, IP address, user agent, and source.
- Reward management for points, coupons, cashback, and badges.
- Customer reviews and testimonials.
- Leaderboard based on customer points and successful referrals.

## Tech Stack

- Backend: Laravel 12
- Language: PHP 8.2+
- Frontend tooling: Vite
- Styling: Tailwind CSS 4
- Database: MongoDB using `mongodb/laravel-mongodb`
- Authentication: Laravel Auth plus Google OAuth through Laravel Socialite
- Testing: PHPUnit

## Core User Roles

### Business

Business users can:

- Access the business dashboard.
- Create referral campaigns.
- Edit campaign details.
- Pause, activate, or end campaigns.
- Delete campaigns.
- View overall analytics.
- View analytics for each campaign.
- Track clicks, referrals, conversions, rewards, and source performance.

### Customer

Customer users can:

- Access the customer dashboard.
- Browse active campaigns.
- Generate referral links for campaigns.
- Share referral links with friends.
- Earn rewards after successful referrals.
- Collect points and badges.
- View earned rewards.
- Submit campaign reviews.
- Compete on the public leaderboard.

### New Customer

Users who register through a referral link are stored as `new_customer`. They are treated like customer users for protected customer pages.

## Referral Flow

1. A business creates an active campaign with a reward type, reward value, date range, and optional referral limit.
2. A customer opens the campaign from their dashboard.
3. The system generates a referral URL in this format:

   ```text
   /ref/{referralCode}?campaign={campaignId}
   ```

4. The customer shares the referral link.
5. When someone clicks the link, OutReach records a click with tracking details.
6. The visitor is shown a referral signup page.
7. After signup, OutReach creates a converted referral record.
8. The original customer receives the configured reward.
9. The customer's points are increased.
10. Badge milestones are checked:

- 5 successful referrals: Rising Star
- 10 successful referrals: Super Advocate
- 25 successful referrals: Outreach Legend

## Important Pages

### Public Pages

- `/` - Home page
- `/campaigns` - Active campaigns
- `/campaign/{id}` - Campaign details
- `/leaderboard` - Top referrers
- `/reviews` - Customer reviews
- `/ref/{referralCode}` - Referral link handler

### Authentication Pages

- `/login` - Login
- `/register` - Register
- `/auth/google` - Google login redirect
- `/forgot-password` - Request password reset
- `/reset-password/{token}` - Reset password

### Business Pages

- `/business/dashboard` - Business dashboard
- `/business/campaign/create` - Create campaign
- `/business/campaign/{id}/edit` - Edit campaign
- `/business/campaign/{id}/analytics` - Campaign analytics
- `/business/analytics` - Overall analytics

### Customer Pages

- `/customer/dashboard` - Customer dashboard
- `/customer/referral/{campaignId}` - Campaign referral link
- `/customer/rewards` - Customer rewards

## Data Models

### User

Stores account information, role, referral code, referrer code, points, badges, Google OAuth data, and business profile fields.

### Campaign

Stores referral campaign details such as business owner, title, description, reward type, reward value, start date, end date, status, and referral limit.

### Referral

Tracks who referred whom, which campaign the referral belongs to, and whether the referral is pending or converted.

### Reward

Stores rewards earned by customers for successful referrals. Supported reward types are points, coupon, cashback, and badge.

### Click

Tracks referral link clicks for analytics, including campaign, referrer, IP address, user agent, and traffic source.

### Review

Stores customer ratings and testimonials for campaigns.

## Installation

Clone the project and install dependencies:

```bash
composer install
npm install
```

Create an environment file:

```bash
cp .env.example .env
php artisan key:generate
```

Configure MongoDB in `.env`:

```env
DB_CONNECTION=mongodb
DB_URI=your_mongodb_connection_string
DB_DATABASE=outreach
```

If you want Google login, also configure:

```env
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/auth/google/callback
```

Run the database seeders:

```bash
php artisan db:seed
```

Start the Laravel server:

```bash
php artisan serve
```

Start the Vite development server in another terminal:

```bash
npm run dev
```

Open the website:

```text
http://127.0.0.1:8000
```

## Useful Commands

Run frontend build:

```bash
npm run build
```

Run tests:

```bash
php artisan test
```

Run the full Laravel development script:

```bash
composer run dev
```

## Demo Login Data

After running the database seeder, you can use these demo accounts.

Business account:

```text
Email: business@outreach.com
Password: password123
```

Customer accounts:

```text
Email: rahul@test.com
Password: password123
```

```text
Email: priya@test.com
Password: password123
```

## Project Structure

```text
app/
  Http/Controllers/      Application controllers
  Http/Middleware/       Role-based access middleware
  Models/                MongoDB Eloquent models

resources/
  views/                 Blade templates
  css/                   Application styles
  js/                    Application JavaScript

routes/
  web.php                Web routes

database/
  seeders/               Demo data seeder
  migrations/            Default Laravel migrations

config/
  database.php           MongoDB database configuration
```

## Summary

OutReach is a complete referral campaign platform. Businesses use it to launch campaigns and monitor campaign performance. Customers use it to share referral links, bring in new users, earn points and rewards, submit reviews, and compete on the leaderboard. The website connects campaign management, referral tracking, rewards, analytics, and customer engagement in one Laravel application.
