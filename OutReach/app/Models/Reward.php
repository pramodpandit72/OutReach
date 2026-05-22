<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;

/**
 * Reward Model
 *
 * Tracks rewards given to users for successful referrals.
 *
 * @property string $user_id - User who received the reward
 * @property string $campaign_id - Campaign this reward is from
 * @property string $type - 'points', 'coupon', 'cashback', 'badge'
 * @property float $value - Numeric value of the reward
 * @property string $description - Human-readable description
 * @property string $status - 'pending', 'claimed', 'expired'
 * @property string|null $coupon_code - Generated coupon code (if type is 'coupon')
 */
class Reward extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'rewards';

    protected $fillable = [
        'user_id',
        'campaign_id',
        'type',
        'value',
        'description',
        'status',
        'coupon_code',
    ];

    protected $casts = [
        'value' => 'float',
    ];

    /**
     * Boot with default status.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($reward) {
            if (is_null($reward->status)) {
                $reward->status = 'pending';
            }
        });
    }

    /**
     * Get the user who owns this reward.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the campaign this reward is from.
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }
}
