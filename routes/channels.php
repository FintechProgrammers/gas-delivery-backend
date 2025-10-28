<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel("request.rider.{riderId}", function () {
    return true;
});

Broadcast::channel('order.accepted.{userId}', function () {
    return true;
});

Broadcast::channel('order.rejected.{userId}', function () {
    return true;
});

Broadcast::channel('trip.started.{userId}', function () {
    return true;
});

Broadcast::channel('trip.completed.{userId}', function () {
    return true;
});
