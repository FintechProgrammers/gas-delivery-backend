<?php

namespace App\Services;

use Berkayk\OneSignal\OneSignalFacade as OneSignal;

class PushNotification
{
    public function sendNotificationToAll($message, $title)
    {
        OneSignal::sendNotificationToAll(
            $message,
            null,
            null,
            null,
            null,
            null,
            $title,
        );
    }

    public function sendNotificationToOne($message, $title, $playerId)
    {
        OneSignal::sendNotificationToUser(
            $message,
            $playerId,
            null,
            null,
            null,
            null,
            $title,
        );
    }
}
