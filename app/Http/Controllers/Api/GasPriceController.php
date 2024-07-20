<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GasPriceResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\VendorResource;
use App\Models\GasPricing;
use App\Models\User;
use Illuminate\Http\Request;

class GasPriceController extends Controller
{
    function index(Request $request)
    {

        $query = GasPricing::query();

        $search = $request->filled('search') ? $request->search : null;
        $address = $request->filled('address') ? $request->address : null;

        $query->when($search, function ($query) use ($search) {
            $query->whereHas('business.businessProfile', function ($query) use ($search) {
                $query->where('business_name', 'LIKE', "%{$search}%");
            });
        })->when($address, function ($query) use ($address) {
            $query->whereHas('business.businessProfile', function ($query) use ($address) {
                $query->where('office_address', 'LIKE', "%{$address}%");
            });
        });

        $priceList = GasPriceResource::collection($query->get());

        return $this->sendResponse($priceList, "Gas Price list");
    }
}
