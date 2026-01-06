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

        // Retrieve the user's latitude, longitude, and optional search term
        $latitude = $validated->latitude;
        $longitude = $validated->longitude;
        $search = $request->query('search'); // Retrieve search query if provided

        $vendor = $this->getNearbyVendors($latitude, $longitude, $search);
        // $vendor = \App\Models\User::where('is_business', true)->get();

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

    private function getNearbyVendors($latitude, $longitude, $search = null)
    {
        $maxDistance = maxDistance();

        $query = DB::table('user_infos')
            ->join('users', 'user_infos.user_id', '=', 'users.id')
            ->whereNotNull('user_infos.latitude')
            ->whereNotNull('user_infos.longitude')
            ->where('users.status', 'active')
            ->select(
                'user_infos.*',  // Select all business details
                'users.uuid as uuid',
                'users.*',
                DB::raw("(
                    6371 * acos(
                        LEAST(1, GREATEST(-1, 
                            cos(radians({$latitude})) *
                            cos(radians(CAST(user_infos.latitude AS DECIMAL(10,8)))) *
                            cos(radians(CAST(user_infos.longitude AS DECIMAL(11,8))) - radians({$longitude})) +
                            sin(radians({$latitude})) *
                            sin(radians(CAST(user_infos.latitude AS DECIMAL(10,8))))
                        ))
                    )
                ) AS distance")
            )
            ->havingRaw('distance < ? AND distance IS NOT NULL', [$maxDistance])
            ->orderBy('distance', 'asc');

        // Add a condition to search by business_name
        if ($search) {
            $query->where('users.business_name', 'like', '%' . $search . '%');
        }

        // Get the data and convert it to a collection of model instances
        $results = $query->get();

        // Map the results to Eloquent model instances
        $nearbyBusinesses = $results->map(function ($item) {
            $user = \App\Models\User::whereId($item->user_id)->where('is_business', true)->where('vendor_fee', '>', 0)->first();

            if (!$user) {
                return null;
            }

            $user->distance = $item->distance;

            return $user->load('profile');
        })->filter();

        return $nearbyBusinesses;
    }
}
