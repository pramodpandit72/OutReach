<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;

/**
 * Referral Model
 *
 * Tracks individual referrals: who referred whom, for which campaign,
 * and the current status (pending, converted).
 *
 * @property string $referrer_id - User ID of the person who shared the referral
 * @property string $referred_id - User ID of the person who signed up via referral
 * @property string $campaign_id - ID of the campaign this referral belongs to
 * @property string $status - 'pending', 'converted'
 * @property \DateTime|null $clicked_at - When the referral link was clicked
 * @property \DateTime|null $converted_at - When the referral was converted (signup/purchase)
 */
class Referral extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'referrals';

    protected $fillable = [
        'referrer_id',
        'referred_id',
        'campaign_id',
        'status',
        'clicked_at',
        'converted_at',
    ];

    protected $casts = [
        'clicked_at' => 'datetime',
        'converted_at' => 'datetime',
    ];

    /**
     * Boot with default status.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($referral) {
            if (is_null($referral->status)) {
                $referral->status = 'pending';
            }
        });
    }

    /**
     * Get the user who made the referral.
     */
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    /**
     * Get the user who was referred.
     */
    public function referred()
    {
        return $this->belongsTo(User::class, 'referred_id');
    }

    /**
     * Get the campaign this referral belongs to.
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }

    /**
     * Mark this referral as converted.
     */
    public function markAsConverted(): void
    {
        $this->update([
            'status' => 'converted',
            'converted_at' => now(),
        ]);
    }
}
