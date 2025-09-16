<?php

namespace App\Models;

use App\Traits\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revenue extends Model
{
    use HasFactory, GeneratesUuid;

    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(GasOrder::class, 'order_id');
    }
}
