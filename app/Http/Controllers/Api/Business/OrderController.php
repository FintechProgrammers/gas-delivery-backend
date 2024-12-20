<?php

namespace App\Http\Controllers\Api\Business;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\GasOrder;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    function index(Request $request)
    {
        $orders = GasOrder::where('business_id', $request->user()->id)->get();

        $orders = OrderResource::collection($orders);

        return $this->sendResponse($orders, 'Orders retrieved successfully');
    }
}
