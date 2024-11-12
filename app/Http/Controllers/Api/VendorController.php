<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NearbyVendorRequest;
use App\Http\Resources\VendorResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    function index(NearbyVendorRequest $request)
    {
        // Validate the incoming request
        $validated = (object) $request->validated();

        // Retrieve the user's latitude and longitude
        $latitude = $validated->latitude;
        $longitude = $validated->longitude;

        $vendor = $this->getNearbyVendors($latitude, $longitude);

        $vendor = VendorResource::collection($vendor);

        return $this->sendResponse($vendor, "Vendor list ");
    }

    function show(User $user)
    {
        $vendor = User::where('id', $user->id)->where('is_business', true)->whereHas('pricing')->first();

        if (!$vendor) {
            return $this->sendError("please select a vendor.", [], 404);
        }

        $vendor = new VendorResource($user);

        return $this->sendResponse($vendor);
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
