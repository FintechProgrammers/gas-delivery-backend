<?php

namespace App\Models;

use App\Traits\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory, GeneratesUuid;

    protected $guarded = [];

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

    function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id', 'id');
    }

    function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }
}
