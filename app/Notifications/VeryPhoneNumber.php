<?php

namespace App\Notifications;

use App\Channels\Messages\WhatsAppMessage;
use App\Channels\WhatsAppChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VeryPhoneNumber extends Notification
{
    use Queueable;

    public function __construct(public $token)
    {
    }

    public function via($notifiable)
    {
        return [WhatsAppChannel::class];
    }

    public function toWhatsApp($notifiable)
    {
        $company = config('app.name');
        $token = $this->token;

        return (new WhatsAppMessage)
            ->content("Your verification code for {$company} is: {$token}. Please use this code to verify your phone number.");
    }
}
