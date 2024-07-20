<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VendorResource;
use App\Models\User;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    function index(Request $request)
    {
        $query = User::query();

        $search = $request->filled('search') ? $request->search : null;

        $query->when($search, function ($query) use ($search) {
            $query->whereHas('businessProfile', function ($query) use ($search) {
                $query->where('business_name', 'LIKE', "%{$search}%");
            });
        });

        $vendor = $query->where('is_business', true)->whereHas('pricing')->get();

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
}
