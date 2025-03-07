<?php

namespace App\Http\Controllers\Api;

use App\Events\DriverRquest;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\RequestRider;
use App\Http\Resources\OrderResource;
use App\Http\Resources\RiderResource;
use App\Http\Resources\UserResource;
use App\Models\DeliveryAddress;
use App\Models\GasPricing;
use App\Models\GasOrder;
use App\Models\OrderRider;
use App\Models\Transaction;
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

    public function getNearbyRiders(Request $request)
    {
        // Get the authenticated user's location from UserInfo
        $user = $request->user();
        $userLatitude = $user->profile->latitude;
        $userLongitude = $user->profile->longitude;

        // Radius in kilometers
        $radius = 30;

        // Haversine formula to calculate distance
        $riders = User::where('account_type', 'RIDER')
            ->where('is_available', true)
            ->join('user_infos', 'users.id', '=', 'user_infos.user_id') // Join UserInfo table
            ->selectRaw(
                'users.*, 
            (6371 * acos(cos(radians(?)) * cos(radians(user_infos.latitude)) * cos(radians(user_infos.longitude) - radians(?)) + sin(radians(?)) * sin(radians(user_infos.latitude)))) AS distance',
                [$userLatitude, $userLongitude, $userLatitude]
            )
            ->having('distance', '<', $radius)
            ->orderBy('distance')
            ->get();

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
}
