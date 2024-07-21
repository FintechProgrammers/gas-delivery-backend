<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Twilio\Rest\Client;

class WhatsAppChannel
{
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toWhatsApp($notifiable);

        $to = $notifiable->routeNotificationFor('WhatsApp');

        $from = config('constant.twilio.whatsapp_from');

        $twilio = new Client(config('constant.twilio.sid'), config('constant.twilio.token'));

        return $twilio->messages->create('whatsapp:' . $to, [
            "from" => "whatsapp:{$from}",
            "body" => $message->content
        ]);
    }
}
