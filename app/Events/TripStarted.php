<?php

namespace App\Events;

use App\Models\User;
use App\Models\GasOrder;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TripStarted implements ShouldBroadcast
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
            new Channel('trip.started.' . $this->user->id),
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
            'message' => 'Your trip has started.',
            'order' => [
                'id' => $this->order->uuid,
                'status' => $this->order->status,
                'initial_cylinder_size' => $this->order->initial_cylinder_size,
            ],
            'rider' => [
                'id' => $this->rider->uuid,
                'name' => $this->rider->name,
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
        return 'trip.started';
    }
}
