<?php

namespace App\Http\Controllers\Api\Business;

use App\Http\Controllers\Controller;
use App\Http\Requests\GasPriceRequest;
use App\Http\Resources\GasPriceResource;
use App\Models\GasPricing;
use Illuminate\Http\Request;

class GasPricingController extends Controller
{
    function index(Request $request)
    {
        $user = $request->user();

        $gasPrice = GasPricing::where('business_id', $user->id)->first();

        $gasPrice =  new GasPriceResource($gasPrice);

        return $this->sendResponse($gasPrice);
    }

    function store(GasPriceRequest $request)
    {
        $validated = (object) $request->validated();

        try {
            $user = $request->user();

            GasPricing::updateOrCreate(
                ['business_id' => $user->id],
                ['price' => $validated->price, 'kg' => 1]
            );

            return $this->sendResponse([], "Gas Pricing updated successfully", 201);
        } catch (\Exception $e) {

            sendToLog($e);

            return $this->sendError(serviceDownMessage(), [], 500);
        }
    }
}
