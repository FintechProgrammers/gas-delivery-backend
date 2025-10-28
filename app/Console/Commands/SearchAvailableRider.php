<?php

namespace App\Console\Commands;

use App\Jobs\AssignRiderToOrder;
use App\Models\GasOrder;
use Illuminate\Console\Command;

class SearchAvailableRider extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:search-available-rider';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //get all pending orders 
        $orders = GasOrder::where('status', 'pending')->get();

        foreach ($orders as $order) {
            // dispatch(new AssignRiderToOrder($order));
        }
    }
}
