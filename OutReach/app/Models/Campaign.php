<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;

/**
 * Campaign Model
 *
 * Represents a referral campaign created by a business user.
 *
 * @property string $business_id - ID of the business user who created the campaign
 * @property string $title - Campaign title
 * @property string $description - Campaign description
 * @property string $reward_type - 'points', 'coupon', 'cashback', 'badge'
 * @property float $reward_value - Numeric value of the reward
 * @property string $reward_description - Human-readable reward description
 * @property string $start_date - Campaign start date
 * @property string $end_date - Campaign end date
 * @property string $status - 'active', 'paused', 'ended'
 * @property int $max_referrals - Maximum referrals allowed per customer (0 = unlimited)
 * @property string|null $image - Campaign banner image URL
 */
class Campaign extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'campaigns';

    protected $fillable = [
        'business_id',
        'title',
        'description',
        'reward_type',
        'reward_value',
        'reward_description',
        'start_date',
        'end_date',
        'status',
        'max_referrals',
        'image',
    ];

    protected $casts = [
        'reward_value' => 'float',
        'max_referrals' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Boot method with default values.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($campaign) {
            if (is_null($campaign->status)) {
                $campaign->status = 'active';
            }
            if (is_null($campaign->max_referrals)) {
                $campaign->max_referrals = 0;
            }
        });
    }

    /**
     * Get the business owner of this campaign.
     */
    public function business()
    {
        return $this->belongsTo(User::class, 'business_id');
    }

    /**
     * Get all referrals for this campaign.
     */
    public function referrals()
    {
        return $this->hasMany(Referral::class, 'campaign_id');
    }

    /**
     * Get all rewards issued for this campaign.
     */
    public function rewards()
    {
        return $this->hasMany(Reward::class, 'campaign_id');
    }

    /**
     * Get all reviews for this campaign.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'campaign_id');
    }

    /**
     * Get all click tracking records for this campaign.
     */
    public function clicks()
    {
        return $this->hasMany(Click::class, 'campaign_id');
    }

    /**
     * Check if campaign is currently active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active'
            && $this->start_date <= now()
            && $this->end_date >= now();
    }

    /**
     * Get the total number of clicks.
     */
    public function totalClicks(): int
    {
        return $this->clicks()->count();
    }

    /**
     * Get the total number of signups (referrals).
     */
    public function totalSignups(): int
    {
        return $this->referrals()->count();
    }

    /**
     * Get the total number of conversions.
     */
    public function totalConversions(): int
    {
        return $this->referrals()->where('status', 'converted')->count();
    }

    /**
     * Get conversion rate as a percentage.
     */
    public function conversionRate(): float
    {
        $clicks = $this->totalClicks();
        if ($clicks === 0) return 0;
        return round(($this->totalConversions() / $clicks) * 100, 1);
    }
}
