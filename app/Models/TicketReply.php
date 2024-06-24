<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketReply extends Model
{
    use HasFactory;

    protected $guarded = [];

    function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    function attachments()
    {
        return $this->hasMany(TicketAttachment::class,'ticket_replies_id','id');
    }
}
