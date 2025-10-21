<?php

namespace App\Events;

use App\Models\GasOrder;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RiderAcceptedOrder implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $order;
    public $rider;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, GasOrder $order, User $rider)
    {
        $this->user = $user;
        $this->order = $order;
        $this->rider = $rider;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // Broadcast to a private channel specific to the user who made the order
        return [
            new Channel('order.accepted.' . $this->user->uuid),
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return [
            'message' => "{$this->rider->full_name} has accepted your order and is on their way.",
            'order' => [
                'id' => $this->order->uuid,
                'status' => $this->order->status,
            ],
            'rider' => [
                'id' => $this->rider->uud,
                'name' => $this->rider->full_name,
                'latitude' => $this->rider->profile->latitude,
                'longitude' => $this->rider->profile->longitude,
            ],
        ];
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'rider.accepted.order';
    }
}
