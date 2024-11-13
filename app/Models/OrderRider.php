<?php

namespace App\Models;

use App\Traits\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderRider extends Model
{
    use HasFactory, GeneratesUuid;

    protected $guarded = [];

    function rider()
    {
        return $this->belongsTo(User::class, 'rider_id', 'id');
    }

    function order()
    {
        return $this->belongsTo(GasOrder::class, 'order_id', 'id');
    }

    /**
     * Define the route model binding key for a given model.
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
