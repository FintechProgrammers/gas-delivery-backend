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
        //get nearest available rider
        // Haversine formula to calculate distance
        $riders = getNearbyAvailableRiders($this->order->delivery_latitude, $this->order->delivery_longitude);

        //send notification to riders about new order
        foreach ($riders as $rider) {

            OrderRider::updateOrCreate(['rider_id' => $rider->id, 'order_id' => $this->order->id], ['status' => 'pending']);

            broadcast(new \App\Events\DriverRquest($rider, $this->order));
        }
    }
}
