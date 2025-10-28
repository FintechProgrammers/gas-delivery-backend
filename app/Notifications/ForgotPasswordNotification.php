<?php

namespace App\Notifications;

use App\Channels\Messages\WhatsAppMessage;
use App\Channels\WhatsAppChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ForgotPasswordNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public $token) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return [WhatsAppChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */

    public function toWhatsApp($notifiable)
    {
        $company = config('app.name');
        $token = $this->token;

        return (new WhatsAppMessage)
            ->content("You requested a password reset for your {$company} account. Your reset code is: {$token}. If you did not request this, please ignore this message.");
    }
}
