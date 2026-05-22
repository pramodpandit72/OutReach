<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;

/**
 * Review Model
 *
 * Stores customer reviews/testimonials for campaigns.
 *
 * @property string $user_id - User who wrote the review
 * @property string $campaign_id - Campaign being reviewed
 * @property int $rating - Rating from 1-5
 * @property string $comment - Review text
 * @property bool $is_approved - Whether business has approved this review
 */
class Review extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'reviews';

    protected $fillable = [
        'user_id',
        'campaign_id',
        'rating',
        'comment',
        'is_approved',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_approved' => 'boolean',
    ];

    /**
     * Boot with default values.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($review) {
            if (is_null($review->is_approved)) {
                $review->is_approved = true;
            }
        });
    }

    /**
     * Get the user who wrote this review.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the campaign this review is for.
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }
}
