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

class RiderRejectedOrder implements ShouldBroadcast
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
            new Channel('order.rejected.' . $this->user->uuid),
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
            'message' => 'A rider has rejected your order.',
            'order' => [
                'id' => $this->order->uuid,
                'status' => $this->order->status,
            ],
            'rider' => [
                'id' => $this->rider->uuid,
                'name' => $this->rider->full_name,
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
        return 'rider.rejected.order';
    }
}
