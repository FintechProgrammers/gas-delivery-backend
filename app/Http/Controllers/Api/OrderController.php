<?php

namespace App\Http\Controllers\Api;

use App\Events\DriverRquest;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\RequestRider;
use App\Http\Resources\OrderResource;
use App\Http\Resources\RiderResource;
use App\Models\GasOrder;
use App\Models\OrderRider;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    function index(Request $request)
    {
        $user = $request->user();

        $orders = GasOrder::where('user_id', $user->id)->get();

        $orders = OrderResource::collection($orders);

        return $this->sendResponse($orders, "", Response::HTTP_OK);
    }

    public function estimateDelivery(Request $request)
    {
        $request->validate([
            'distance' => 'required|numeric|min:0'
        ]);

        $fee = calculateDeliveryFee($request->distance);

        return $this->sendResponse(['delivery_fee' => $fee], 'Delivery fee calculated');
    }

    public function markAsPaid(GasOrder $order)
    {
        if ($order->is_paid) {
            return $this->sendError("Order is already marked as paid", [], Response::HTTP_NOT_ACCEPTABLE);
        }

        $order->update(['is_paid' => true]);

        return $this->sendResponse([], "Order marked as paid successfully", Response::HTTP_OK);
    }

    /**
     * Place a new order.
     *
     * @param OrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function placeOrder(OrderRequest $request)
    {

        $user = $request->user();

        $requestData = $request->validated();

        $result = $this->orderService->placeOrder($user, $requestData);

        if ($result['success']) {
            return $this->sendResponse($result['data'], $result['message'], $result['status']);
        }

        return $this->sendError($result['message'], [], $result['status']);
    }

    function orderDetails(GasOrder $order)
    {
        $order = new OrderResource($order);

        return $this->sendResponse($order, "", Response::HTTP_OK);
    }

    function requestRider(RequestRider $request)
    {
        try {
            DB::beginTransaction();

            $rider = User::whereUuid($request->rider)->where('account_type', 'RIDER')->where('is_available', true)->first();

            if (!$rider) {
                return $this->sendError("Rider not available", [], Response::HTTP_NOT_FOUND);
            }

            $order = GasOrder::whereUuid($request->order)->where('status', 'pending')->first();

            if (!$order) {
                return $this->sendError("Invalid Order", [], Response::HTTP_NOT_FOUND);
            }

            OrderRider::updateOrCreate(['rider_id' => $rider->id, 'order_id' => $order->id], ['status' => 'pending']);

            broadcast(new DriverRquest($rider, $order));

            DB::commit();

            return $this->sendResponse([], "Rider Requested successfully", Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            logger($e);

            return $this->sendError(serviceDownMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getNearbyRiders(Request $request, GasOrder $order)
    {

        $userLongitude = $order->deliveryAddress?->longitude;
        $userLatitude  = $order->deliveryAddress?->latitude;

        // Haversine formula to calculate distance
        $riders = getNearbyAvailableRiders($userLatitude, $userLongitude);

        // Transform the riders using a resource
        $riders = RiderResource::collection($riders);

        return $this->sendResponse($riders, "Riders within 30km radius", Response::HTTP_OK);
    }

    /**
     * Complete an order.
     *
     * @param GasOrder $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function complete(GasOrder $order)
    {
        $result = $this->orderService->completeOrder($order);

        if ($result['success']) {
            return $this->sendResponse([], $result['message'], $result['status']);
        }

        return $this->sendError($result['message'], [], $result['status']);
    }

    public function getOrderTimeline(GasOrder $order)
    {
        // Get completed milestones
        $completedMilestones = $order->timeline->pluck('status')->toArray();

        $milestones = milestones();

        // Prepare timeline data
        $timelineData = [];
        foreach ($milestones as $status => $data) {
            $timelineData[] = [
                'label' => $data['label'],
                'status' => $status,
                'description' => $data['description'], // Add description here
                'completed' => in_array($status, $completedMilestones),
                'timestamp' => $order->timeline->where('status', $status)->first()->status_time ?? null,
            ];
        }

        return $this->sendResponse($timelineData);
    }

    /**
     * Cancel a gas order.
     *
     * @param GasOrder $order The order to be canceled.
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelOrder(GasOrder $order)
    {
        // Check if the order is already canceled or cannot be canceled
        if (!empty($order->rider_id)) {
            return $this->sendError(
                "This order cannot be canceled because it is not in a 'pending' state.",
                [],
                Response::HTTP_NOT_ACCEPTABLE
            );
        }

        try {
            // Update the order status to 'cancelled'
            $order->update(['status' => 'cancelled']);

            // Return a success response
            return $this->sendResponse(
                [],
                "Order successfully canceled.",
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            sendToLog("Failed to cancel order: " . $e->getMessage());

            // Return a generic error response
            return $this->sendError(
                "An error occurred while canceling the order. Please try again later.",
                [],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
