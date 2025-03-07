<?php

namespace App\Http\Controllers\Api\Rider;

use App\Events\RiderAcceptedOrder;
use App\Events\RiderRejectedOrder;
use App\Events\TripCompleted;
use App\Events\TripStarted;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Resources\RiderOrderResource;
use App\Models\GasOrder;
use App\Models\OrderRider;
use App\Models\OrderTimeline;
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

    public function acceptOrder(Request $request, GasOrder $order)
    {
        try {
            // Ensure there is an associated gas order

            $rider = $request->user();

            $user = $order->user;

            DB::beginTransaction();

            // Update the OrderRider and the associated GasOrder
            $order->update(['rider_id' => $rider->id]);

            OrderRider::where('order_id', $order->id)->where('rider_id', $rider->id)->update(['status' => 'accepted']);

            $rider->update([
                'is_available' => false
            ]);

            // Trigger the event
            event(new RiderAcceptedOrder($user, $order, $rider));

            DB::commit();

            $order = new OrderResource($order);

            return $this->sendResponse($order, "Order accepted successfully", Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error("Failed to accept order: " . $e->getMessage(), ['exception' => $e]);

            return $this->sendError(serviceDownMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function rejectOrder(Request $request, GasOrder $order)
    {
        try {
            // Ensure there is an associated gas order
            $rider = $request->user();

            $user = $order->user;

            DB::beginTransaction();

            OrderRider::where('order_id', $order->id)->where('rider_id', $rider->id)->delete();

            $rider->update([
                'is_available' => true
            ]);

            $order->update(['rider_id' => null]);

            // Trigger the event
            event(new RiderRejectedOrder($user, $order, $rider));

            DB::commit();

            return $this->sendResponse([], "Order rejected successfully", Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error("Failed to reject order: " . $e->getMessage(), ['exception' => $e]);

            return $this->sendError(serviceDownMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function startTripe(Request $request, GasOrder $order)
    {
        $request->validate([
            'cylinder_size' => 'required|string'
        ]);

        try {
            // Ensure there is an associated gas order
            $rider = $request->user();

            $user = $order->user;

            DB::beginTransaction();

            $order->update([
                'status' => 'active',
                'initial_cylinder_size' => $request->cylinder_size
            ]);

            // Trigger the TripStarted event
            event(new TripStarted($user, $order, $rider));

            DB::commit();

            return $this->sendResponse([], "Order started successfully", Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error("Failed to start order: " . $e->getMessage(), ['exception' => $e]);

            return $this->sendError(serviceDownMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function completeTripe(Request $request, GasOrder $order)
    {
        $request->validate([
            'cylinder_size' => 'required|string'
        ]);

        try {
            // Ensure there is an associated gas order
            $rider = $request->user();

            $user = $order->user;

            DB::beginTransaction();

            $order->update([
                'status' => 'active',
                'final_cylinder_size' => $request->cylinder_size
            ]);

            // Trigger the TripCompleted event
            event(new TripCompleted($user, $order, $rider));

            DB::commit();

            return $this->sendResponse([], "Order completed successfully", Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error("Failed to start order: " . $e->getMessage(), ['exception' => $e]);

            return $this->sendError(serviceDownMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateStatus(Request $request, GasOrder $order)
    {
        // Validate the new status
        $request->validate([
            'status' => 'required',
        ]);

        // Add a new entry to the timeline
        OrderTimeline::create([
            'order_id' => $order->id,
            'status' => $request->status,
            'status_time' => now(),
        ]);

        sendPushNotification($order->user, "Your rider has updated the delivery status", "Order Status Updated");

        return response()->json(['message' => 'Order status updated successfully']);
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

    function getTimelineStatus()
    {
        $timelineStatus = milestones();

        return $this->sendResponse($timelineStatus);
    }
}
