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
}
