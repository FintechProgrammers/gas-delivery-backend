<?php

namespace App\Models;

use App\Traits\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory, GeneratesUuid;

    protected $guarded = [];

    /**
     * Get the balance attribute.
     *
     * @param  int  $value
     * @return float
     */
    public function getBalanceAttribute($value)
    {
        // Convert the stored integer balance to a decimal value
        return $value / 100;
    }

    /**
     * Set the balance attribute.
     *
     * @param  float  $value
     * @return void
     */
    public function setBalanceAttribute($value)
    {
        // Convert the decimal balance to an integer value before storing
        $this->attributes['balance'] = $value * 100;
    }
}
