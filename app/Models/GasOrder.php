<?php

namespace App\Models;

use App\Traits\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GasOrder extends Model
{
    use HasFactory, GeneratesUuid, SoftDeletes;

    protected $guarded = [];

    const STATUS = [
        'pending' => 'pending',
        'active' => 'active',
        'completed' => 'completed',
        'cancelled' => 'cancelled'
    ];

    function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    function business()
    {
        return $this->belongsTo(User::class, 'business_id', 'id');
    }

    function rider()
    {
        return $this->belongsTo(User::class, 'rider_id', 'id');
    }

    function deliveryAddress()
    {
        return $this->hasOne(DeliveryAddress::class, 'delivery_address_id', 'id');
    }

    /**
     * Define the route model binding key for a given model.
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'from_distination' => 'array',
            'to_distination' => 'array'
        ];
    }
}
