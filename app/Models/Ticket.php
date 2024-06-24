<?php

namespace App\Models;

use App\Traits\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, GeneratesUuid, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * Define the route model binding key for a given model.
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    function subject()
    {
        return $this->belongsTo(SupportSubject::class,'support_subject_id','id');
    }

    function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    function replys()
    {
        return $this->hasMany(TicketReply::class);
    }
}
