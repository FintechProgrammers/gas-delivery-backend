<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Channels\Messages\WhatsAppMessage;
use App\Channels\WhatsAppChannel;

class TransactionTokenReset extends Notification
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
    public function via(object $notifiable): array
    {
        return [WhatsAppChannel::class];
    }

    public function toWhatsApp($notifiable)
    {
        $company = config('app.name');
        $token = $this->token;

        return (new WhatsAppMessage)
            ->content("Your {$company} pin reset token : {$token}. Please use this code to reset your transaction pin.");
    }
}
