<?php

namespace App\Models;

use App\Mail\AdminResetPassword;
use App\Traits\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasFactory, GeneratesUuid, HasRoles, SoftDeletes, Notifiable;

    protected $guarded = [];

    /**
     * Define the route model binding key for a given model.
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function getProfilePictureAttribute(): string
    {
        return !empty($this->profile_image) ? $this->profile_image : url('/') . '/assets/images/avatar.svg';
    }

    // public function sendPasswordResetNotification($token)
    // {
    //     // dispatch(new \App\Jobs\Mail\AdminResetPasswordJob(['token' => $token, 'email' => $this->email]));

    //     $body = new AdminResetPassword($token);

    //     $email = $this->email;

    //     $sent = sendMailByDriver('mailgun', $email, $body);

    //     // Send mail via send grid driver if mailgun fails
    //     if (!$sent) {
    //         sendMailByDriver('smtp', $email, $body);
    //     }
    // }
}
