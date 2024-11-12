<?php

namespace App\Models;

use App\Traits\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Delivery extends Model
{
    use HasFactory, GeneratesUuid, SoftDeletes;

    protected $guarded = [];

    const STATUS = [
        'pending' => 'pending',
        'active' => 'active',
        'completed' => 'completed',
        'cancelled' => 'cancelled'
    ];

    /**
     * Define the route model binding key for a given model.
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
