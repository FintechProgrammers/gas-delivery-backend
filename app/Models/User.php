<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;
// use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable, GeneratesUuid, SoftDeletes;

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
        'first_name',
        'last_name',
        'email',
        'business_name'
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

    public function getProfilePictureAttribute(): string
    {
        return !empty($this->profile_image) ? $this->profile_image : url('/') . '/assets/images/avatar.svg';
    }

    // public function getAccountTypeAttribute(): string
    // {
    //     return $this->is_business ? "BUSINESS" : "PERSONAL";
    // }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->referral_code = static::generateReferralCode();
        });

        static::created(function ($user) {
            if ($user->is_business) {
                BusinessInfo::create(['user_id' => $user->id]);
            }

            Wallet::create(['user_id' => $user->id]);
        });
    }

    function profile()
    {
        return $this->hasOne(UserInfo::class, 'user_id', 'id');
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

    function bonusWallet()
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

    function deliveryAddress()
    {
        return $this->hasOne(DeliveryAddress::class, 'user_id', 'id');
    }

    function bankAccounts()
    {
        return $this->hasMany(BankAccount::class, 'user_id')->latest();
    }

    /**
     * Get the full name of the user.
     */
    public function getFullNameAttribute(): string
    {
        if ($this->is_business) {
            return !empty($this->business_name) ? \Illuminate\Support\Str::title($this->business_name) : 'Unavailable';
        } else {
            return !empty($this->first_name) ? \Illuminate\Support\Str::title($this->first_name . ' ' . $this->last_name) : 'Unavailable';
        }
    }

    public function routeNotificationForWhatsApp()
    {
        return $this->phone_number;
    }

    function pricing()
    {
        return $this->hasMany(GasPricing::class, 'business_id', 'id')->latest();
    }

    function pricePerKg()
    {
        return $this->hasOne(PricePerKg::class, 'user_id', 'id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'user_id');
    }

    public function givenRatings()
    {
        return $this->hasMany(Rating::class, 'rated_by');
    }

    // Accessor for 5-star rating percentage
    public function getStarRatingAttribute()
    {
        $totalRatings = $this->ratings()->count();
        if ($totalRatings === 0) {
            return 0; // Avoid division by zero
        }

        $fiveStarRatings = $this->five_star_rating_count;
        return round(($fiveStarRatings / $totalRatings) * 100, 2);
    }

    function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id', 'id')->latest();
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'transaction_pin'
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
            'phone_number_verified_at' => 'datetime',
            'bvn_verified_at' => 'datetime',
            'kyc_verified_at' => 'datetime',
            'password' => 'hashed',
            'transaction_pin' => 'hashed'
        ];
    }
}
