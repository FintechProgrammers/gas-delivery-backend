<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable, GeneratesUuid, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    protected $primaryKey = 'id';
    // public $incrementing = false;

    // Define the required fields for the user model and the related userInfo model
    protected $userRequiredFields = [
        'name',
        'email',
        'username',
    ];

    protected $userInfoRequiredFields = [
        'country_code',
    ];

    /**
     * Define the route model binding key for a given model.
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    function userProfile()
    {
        return $this->hasOne(UserInfo::class, 'user_id');
    }

    public function getProfilePictureAttribute(): string
    {
        return !empty($this->profile_image) ? $this->profile_image : url('/') . '/assets/images/avatar.svg';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->referral_code = static::generateReferralCode();
        });

        static::created(function ($user) {
            UserInfo::create(['user_id' => $user->id]);
            Wallet::create(['user_id' => $user->id, 'amount' => 0]);
        });
    }

    public static function generateReferralCode()
    {
        do {
            $code = Str::random(10);
        } while (self::where('referral_code', $code)->exists());

        return $code;
    }

    /**
     * Calculate the profile completion percentage.
     *
     * @return float The percentage of profile completion.
     */
    public function getProfileCompletionPercentageAttribute()
    {
        $filledFields = 0;

        // Check fields in User model
        foreach ($this->userRequiredFields as $field) {
            if (!empty($this->$field)) {
                $filledFields++;
            }
        }

        // Check fields in UserInfo model
        $userInfo = $this->userProfile;
        if ($userInfo) {
            foreach ($this->userInfoRequiredFields as $field) {
                if (!empty($userInfo->$field)) {
                    $filledFields++;
                }
            }
        }

        // Total required fields
        $totalFields = count($this->userRequiredFields) + count($this->userInfoRequiredFields);
        if ($totalFields === 0) {
            return 100; // If no required fields are defined, consider profile as fully complete
        }

        return ($filledFields / $totalFields) * 100;
    }

    function bonuWallet()
    {
        return $this->hasOne(Bonus::class, 'user_id', 'id');
    }

    function wallet()
    {
        return $this->hasOne(Wallet::class, 'user_id', 'id');
    }

    function activities()
    {
        return $this->hasMany(UserActivities::class, 'user_id', 'id')->latest();
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
