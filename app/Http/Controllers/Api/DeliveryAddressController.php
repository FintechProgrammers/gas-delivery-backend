<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeliveryAddressRequest;
use App\Http\Resources\DeliveryAddressResource;
use App\Models\DeliveryAddress;
use Illuminate\Http\Request;

class DeliveryAddressController extends Controller
{
    function index(Request $request)
    {
        $user = $request->user();

        $address = DeliveryAddressResource::collection(DeliveryAddress::where('user_id', $user->id)->latest()->get());

        return $this->sendResponse($address);
    }

    function store(DeliveryAddressRequest $request)
    {
        try {

            $user = $request->user();

            $address = DeliveryAddress::create([
                'user_id' => $user->id,
                'country_id' => $request->country,
                'state' => $request->state,
                'city' => $request->city,
                'house_number' => $request->house_number,
                'street' => $request->street,
                'nearest_land_mark' => $request->nearest_land_mark
            ]);

            $address = new DeliveryAddressResource($address);

            return $this->sendResponse($address, "Delivery Address created successfully", 201);
        } catch (\Exception $e) {

            sendToLog($e);

            return $this->sendError(serviceDownMessage(), [], 500);
        }
    }

    function show(DeliveryAddress $address)
    {
        $address = new DeliveryAddressResource($address);

        return $this->sendResponse($address, "", 200);
    }

    function update(DeliveryAddressRequest $request, DeliveryAddress $address)
    {
        try {

            $address->update([
                'country_id' => $request->country,
                'state' => $request->state,
                'city' => $request->city,
                'house_number' => $request->house_number,
                'street' => $request->street,
                'nearest_land_mark' => $request->nearest_land_mark
            ]);

            $address = new DeliveryAddressResource($address->refresh());

            return $this->sendResponse($address, "Updated successfully", 201);
        } catch (\Exception $e) {

            sendToLog($e);

            return $this->sendError(serviceDownMessage(), [], 500);
        }
    }

    function destroy(DeliveryAddress $address)
    {
        $address->delete();

        return $this->sendResponse([], "Delete successfully", 200);
    }
}
