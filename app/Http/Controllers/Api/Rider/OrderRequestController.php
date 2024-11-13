<?php

namespace App\Http\Controllers\Api\Rider;

use App\Http\Controllers\Controller;
use App\Http\Resources\RiderOrderResource;
use App\Models\GasOrder;
use App\Models\OrderRider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class OrderRequestController extends Controller
{
    function index(Request $request)
    {
        $user = $request->user();

        $orderRequest = OrderRider::where('rider_id', $user->id)->get();

        $orderRequest = RiderOrderResource::collection($orderRequest);

        return $this->sendResponse($orderRequest, "", Response::HTTP_OK);
    }

    public function acceptOrder(OrderRider $order)
    {
        try {
            // Ensure there is an associated gas order
            $gasOrder = $order->order;
            $rider = $order->user;

            if (!$gasOrder) {
                return $this->sendError("Associated order not found", [], Response::HTTP_NOT_FOUND);
            }

            DB::beginTransaction();

            // Update the OrderRider and the associated GasOrder
            $order->update(['status' => 'accepted']);

            $gasOrder->update(['rider_id' => $order->rider_id, 'status' => 'active']);

            $rider->update([
                'is_available' => false
            ]);

            DB::commit();

            return $this->sendResponse([], "Order accepted successfully", Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error("Failed to accept order: " . $e->getMessage(), ['exception' => $e]);

            return $this->sendError(serviceDownMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function rejectOrder(OrderRider $order)
    {
        try {
            // Ensure there is an associated gas order
            $gasOrder = $order->order;
            $rider = $order->user;

            if (!$gasOrder) {
                return $this->sendError("Associated order not found", [], Response::HTTP_NOT_FOUND);
            }

            DB::beginTransaction();

            // Reset rider_id and status on the gas order, then delete the OrderRider record
            $gasOrder->update(['rider_id' => null, 'status' => 'pending']);
            $order->delete();

            $rider->update([
                'is_available' => true
            ]);

            DB::commit();

            return $this->sendResponse([], "Order rejected successfully", Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error("Failed to reject order: " . $e->getMessage(), ['exception' => $e]);

            return $this->sendError(serviceDownMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
