<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NearbyVendorRequest;
use App\Http\Resources\GasPriceResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\VendorResource;
use App\Models\GasPricing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GasPriceController extends Controller
{
    function index(NearbyVendorRequest $request)
    {
        // Validate the incoming request
        $validated = (object) $request->validated();

        // Retrieve the user's latitude and longitude
        $latitude = $validated->latitude;
        $longitude = $validated->longitude;
        $search = $request->search;

        $nearbyVendors = $this->getNearbyVendors($latitude, $longitude);

        $vendorIds = $nearbyVendors->pluck('id')->toArray();

        $query = GasPricing::query()
            ->whereIn('business_id', $vendorIds) // Filter for nearby businesses
            ->when($request->has('search') && $request->filled('search'), function ($query) use ($search) {
                $query->whereHas('business', function ($query) use ($search) {
                    $query->where(
                        'business_name',
                        'LIKE',
                        "%{$search}%"
                    );
                });
            });

        $priceList = GasPriceResource::collection($query->get());

        return $this->sendResponse($priceList, "Gas Price list");
    }

    private function getNearbyVendors($latitude, $longitude)
    {
        $maxDistance = maxDistance();

        $query = DB::table('user_infos')
            ->join('users', 'user_infos.user_id', '=', 'users.id') // Join users table
            ->select(
                'user_infos.*',  // Select all business details
                'users.uuid as uuid',
                'users.*',
                DB::raw("(6371 * acos(cos(radians($latitude))
            * cos(radians(latitude))
            * cos(radians(longitude) - radians($longitude))
            + sin(radians($latitude))
            * sin(radians(latitude)))) AS distance") // Calculate distance
            )
            ->where('users.status', 'active')  // Only select active business information
            ->having('distance', '<=', $maxDistance)  // Filter by max distance
            ->orderBy('distance', 'asc'); // Sort by distance ascending

        // Get the data and convert it to a collection of model instances
        $results = $query->get();

        // Map the results to Eloquent model instances
        $nearbyBusinesses = $results->map(function ($item) {
            $user = \App\Models\User::whereId($item->user_id)->where('is_business', true)->first();
            $user->distance = $item->distance;
            return $user;
        });

        return $nearbyBusinesses;
    }
}
