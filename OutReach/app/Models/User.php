<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

/**
 * User Model
 *
 * Represents a user in the OutReach platform.
 * Supports three roles: business, customer, new_customer
 *
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $role - 'business', 'customer', 'new_customer'
 * @property string $referral_code - Unique referral code for the user
 * @property string|null $referred_by - Referral code of the user who referred this user
 * @property int $points - Reward points accumulated
 * @property string|null $google_id - Google OAuth ID
 * @property string|null $avatar - URL to user's avatar
 * @property string|null $company_name - Business name (for business role)
 * @property string|null $company_description - Business description
 * @property array $badges - List of earned badges
 */
class User extends Eloquent implements AuthenticatableContract
{
    use Authenticatable, Notifiable;

    protected $connection = 'mongodb';
    protected $collection = 'users';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'referral_code',
        'referred_by',
        'points',
        'google_id',
        'avatar',
        'company_name',
        'company_description',
        'badges',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'points' => 'integer',
        'badges' => 'array',
    ];

    /**
     * Boot method to auto-generate a unique referral code on user creation.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->referral_code)) {
                $user->referral_code = self::generateUniqueReferralCode($user->name);
            }
            if (is_null($user->points)) {
                $user->points = 0;
            }
            if (is_null($user->badges)) {
                $user->badges = [];
            }
        });
    }

    /**
     * Generate a unique referral code based on username.
     */
    public static function generateUniqueReferralCode(string $name): string
    {
        $base = Str::slug($name);
        $code = $base . rand(100, 999);

        while (self::where('referral_code', $code)->exists()) {
            $code = $base . rand(100, 9999);
        }

        return $code;
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user is a business/admin.
     */
    public function isBusiness(): bool
    {
        return $this->role === 'business';
    }

    /**
     * Check if user is a customer/advocate.
     */
    public function isCustomer(): bool
    {
        return $this->role === 'customer' || $this->role === 'new_customer';
    }

    /**
     * Get campaigns created by this business user.
     */
    public function campaigns()
    {
        return $this->hasMany(Campaign::class, 'business_id');
    }

    /**
     * Get referrals made by this user (as referrer).
     */
    public function referralsMade()
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    /**
     * Get referrals where this user was referred.
     */
    public function referralsReceived()
    {
        return $this->hasMany(Referral::class, 'referred_id');
    }

    /**
     * Get rewards earned by this user.
     */
    public function rewards()
    {
        return $this->hasMany(Reward::class, 'user_id');
    }

    /**
     * Get reviews written by this user.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'user_id');
    }

    /**
     * Get the user who referred this user.
     */
    public function referrer()
    {
        return self::where('referral_code', $this->referred_by)->first();
    }

    /**
     * Get count of successful referrals.
     */
    public function successfulReferralsCount(): int
    {
        return $this->referralsMade()->where('status', 'converted')->count();
    }
}
