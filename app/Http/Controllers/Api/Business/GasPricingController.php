<?php

namespace App\Http\Controllers\Api\Business;

use App\Http\Controllers\Controller;
use App\Http\Requests\GasPriceRequest;
use App\Http\Requests\PricePerKgRequest;
use App\Http\Resources\GasPriceResource;
use App\Models\GasPricing;
use App\Models\PricePerKg;
use Illuminate\Http\Request;

class GasPricingController extends Controller
{
    function index(Request $request)
    {
        $user = $request->user();

        $gasPrice = GasPricing::where('business_id', $user->id)->get();

        $gasPrice =  GasPriceResource::collection($gasPrice);

        return $this->sendResponse($gasPrice);
    }

    function store(GasPriceRequest $request)
    {
        $validated = (object) $request->validated();

        try {
            $user = $request->user();

            GasPricing::create(['business_id' => $user->id, 'price' => $validated->price, 'kg' =>  $validated->cylinder_size]);

            return $this->sendResponse([], "Gas Pricing updated successfully", 201);
        } catch (\Exception $e) {

            sendToLog($e);

            return $this->sendError(serviceDownMessage(), [], 500);
        }
    }

    function update(GasPriceRequest $request, GasPricing $gasPrice)
    {
        $validated = (object) $request->validated();

        try {

            $gasPrice->update(['price' => $validated->price, 'kg' =>  $validated->cylinder_size]);

            return $this->sendResponse([], "Gas Pricing updated successfully", 201);
        } catch (\Exception $e) {

            sendToLog($e);

            return $this->sendError(serviceDownMessage(), [], 500);
        }
    }

    function setPricePerKg(PricePerKgRequest $request)
    {
        try {

            $user = $request->user();

            PricePerKg::updateOrCreate(['user_id' => $user->id, 'price' => $request->price_per_kg]);

            return $this->sendResponse([], "Price per Kg set successfully", 201);
        } catch (\Exception $e) {

            sendToLog($e);

            return $this->sendError(serviceDownMessage(), [], 500);
        }
    }

    function destroy(GasPricing $gasPrice)
    {

        try {

            $gasPrice->delete();

            return $this->sendResponse([], "Gas Pricing deleted successfully", 201);
        } catch (\Exception $e) {

            sendToLog($e);

            return $this->sendError(serviceDownMessage(), [], 500);
        }
    }
}
