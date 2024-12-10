<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\RequestRider;
use App\Http\Resources\OrderResource;
use App\Models\DeliveryAddress;
use App\Models\GasPricing;
use App\Models\GasOrder;
use App\Models\OrderRider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    function index(Request $request)
    {
        $user = $request->user();

        $orders = GasOrder::where('user_id', $user->id)->get();

        $orders = OrderResource::collection($orders);

        return $this->sendResponse($orders, "", Response::HTTP_OK);
    }

    function placeOrder(OrderRequest $request)
    {
        try {

            DB::beginTransaction();

            $user = $request->user();

            // get vendor
            $business = User::with('profile')->whereUuid($request->vendor)->first();

            if (!$business) {
                return $this->sendError("Invalid vendor selected", [], 404);
            }

            // get user delivery information
            $deliveryAddress = DeliveryAddress::whereUuid($request->delivery_address)->first();

            if (!$deliveryAddress) {
                return $this->sendError("Invalid delivery address", [], 404);
            }

            $stationAddress = [
                'longitude' => $business->profile->longitude,
                'latitude' => $business->profile->latitude
            ];

            $userAddress = [
                'longitude' => $deliveryAddress->longitude,
                'latitude' => $deliveryAddress->latitude
            ];

            $distance = calculateDistance(
                $userAddress['latitude'],
                $userAddress['longitude'],
                $stationAddress['latitude'],
                $stationAddress['longitude']
            );

            $distance = round($distance, 2);

            $pricePerKm = pricePerKm();

            $deliveryFee = $distance * $pricePerKm;

            $pricePerKg = $business->pricePerKg->price;

            $gasAmount =  $pricePerKg * $request->gas_amount;

            $totalAmount = $gasAmount + $deliveryFee;

            GasOrder::create([
                'reference' => generateReference(),
                'user_id' => $user->id,
                'delivery_address_id' => $deliveryAddress->id,
                'business_id' => $business->id,
                'to_distination' => json_encode($stationAddress),
                'from_distination' => json_encode($userAddress),
                'distance' => $distance,
                'delivery_fee' => $deliveryFee,
                'total_amount' => $totalAmount,
                'gas_amount' => $gasAmount, //how much gas to fill
                'gas_size' => $request->gas_amount . 'kg', // how many kg of gas to fill
                'cylinder_size' => $request->cylinder_size . 'kg', // cylinder size of customer
                'price_per_km' => $pricePerKm, // price per km of customer
            ]);

            DB::commit();

            return $this->sendResponse([], "Order place successfully", Response::HTTP_CREATED);
        } catch (\Exception $e) {
            logger($e);
            DB::rollBack();
            return $this->sendError(serviceDownMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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

            DB::commit();

            return $this->sendResponse([], "Rider Requested successfully", Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            logger($e);

            return $this->sendError(serviceDownMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
