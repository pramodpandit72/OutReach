<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;

/**
 * Click Model
 *
 * Tracks individual clicks on referral links for analytics.
 *
 * @property string $campaign_id - Campaign the referral link belongs to
 * @property string $referrer_id - User whose referral link was clicked
 * @property string|null $ip_address - IP address of the clicker
 * @property string|null $user_agent - Browser user agent string
 * @property string|null $source - Social media source (whatsapp, facebook, twitter, etc.)
 */
class Click extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'clicks';

    protected $fillable = [
        'campaign_id',
        'referrer_id',
        'ip_address',
        'user_agent',
        'source',
    ];

    /**
     * Get the campaign this click belongs to.
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }

    /**
     * Get the referrer whose link was clicked.
     */
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }
}
