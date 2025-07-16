<?php

namespace App\Models;

use App\Traits\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessBookmark extends Model
{
    use HasFactory, GeneratesUuid;

    protected $guarded = [];

    function vendor()
    {
        return $this->belongsTo(User::class, 'business_id');
    }

    /**
     * Define the route model binding key for a given model.
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
