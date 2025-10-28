<?php

namespace App\Jobs;

use App\Models\GasOrder;
use App\Models\OrderRider;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AssignRiderToOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected GasOrder $order;

    /**
     * Create a new job instance.
     */
    public function __construct(GasOrder $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        logger("in the search");

        // If the order is already accepted by a rider, do not notify again
        $alreadyAccepted = OrderRider::where('order_id', $this->order->id)
            ->where('status', 'accepted')
            ->exists();

        logger("here");

        if ($alreadyAccepted) {
            return;
        }

        // Check if there is a pending assignment older than 1 minute
        $pendingAssignment = OrderRider::where('order_id', $this->order->id)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->first();

        if ($pendingAssignment && $pendingAssignment->created_at->diffInSeconds(now()) > 60) {
            // Mark the previous assignment as cancelled or rejected
            $pendingAssignment->update(['status' => 'rejected']);
        } elseif ($pendingAssignment) {
            // If still within a minute, do not reassign
            return;
        }

        // Get the nearest available riders who have NOT rejected this order
        $riders = getNearbyAvailableRiders($this->order->delivery_latitude, $this->order->delivery_longitude);
        $eligibleRider = $riders->first(function ($rider) {
            return !OrderRider::where('order_id', $this->order->id)
                ->where('rider_id', $rider->id)
                ->where('status', 'rejected')
                ->exists();
        });

        if ($eligibleRider) {
            OrderRider::updateOrCreate(
                ['rider_id' => $eligibleRider->id, 'order_id' => $this->order->id],
                ['status' => 'pending']
            );
            broadcast(new \App\Events\DriverRquest($eligibleRider, $this->order));
        }
    }
}
