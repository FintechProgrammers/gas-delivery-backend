<?php

namespace App\Models;

use App\Traits\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GasPricing extends Model
{
    use HasFactory, GeneratesUuid;

    protected $guarded = [];

    function business()
    {
        return $this->belongsTo(User::class, 'business_id', 'id');
    }

    /**
     * Define the route model binding key for a given model.
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
