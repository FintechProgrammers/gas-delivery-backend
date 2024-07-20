<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateBusinessDetails;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    function index(Request $request)
    {
        $user = $request->user();

        $user = new UserResource($user);

        return $this->sendResponse($user);
    }

    function update(UpdateProfileRequest $request)
    {
        try {

            $validated = $request->validated();

            $user = $request->user();

            $user->update(array_filter([
                'first_name' => array_key_exists('first_name', $validated) && !empty($validated['first_name']) ? $validated['first_name'] : $user->first_name,
                'middle_name' => array_key_exists('middle_name', $validated) && !empty($validated['middle_name']) ? $validated['middle_name'] : $user->middle_name,
                'last_name' => array_key_exists('last_name', $validated) && !empty($validated['last_name']) ? $validated['last_name'] : $user->last_name,
            ]));

            return $this->sendResponse([], "Profile updated successfully", 201);
        } catch (\Exception $e) {

            sendToLog($e);

            return $this->sendError(serviceDownMessage(), [], 500);
        }
    }

    function updateBusinessProfile(UpdateBusinessDetails $request)
    {
        $validated = $request->validated();

        try {
            $user = $request->user();

            $businessProfile = $user->businessProfile;

            $businessProfile->update(array_filter([
                'business_name' => array_key_exists('business_name', $validated) && !empty($validated['business_name']) ? $validated['business_name'] : $businessProfile->business_name,
                'date_incorporated' => array_key_exists('date_incorporated', $validated) && !empty($validated['date_incorporated']) ? $validated['date_incorporated'] : $businessProfile->date_incorporated,
                'office_address'   => array_key_exists('office_address', $validated) && !empty($validated['office_address']) ? $validated['office_address'] : $businessProfile->office_address,
                'longitude' => array_key_exists('longitude', $validated) && !empty($validated['longitude']) ? $validated['longitude'] : $businessProfile->longitude,
                'latitude' => array_key_exists('latitude', $validated) && !empty($validated['latitude']) ? $validated['latitude'] : $businessProfile->latitude,
                'opening_hours' => array_key_exists('opening_hours', $validated) && !empty($validated['opening_hours']) ? $validated['opening_hours'] : $businessProfile->opening_hours,
            ]));

            return $this->sendResponse([], "Business Profile updated successfully", 201);
        } catch (\Exception $e) {

            sendToLog($e);

            return $this->sendError(serviceDownMessage(), [], 500);
        }
    }

    function updateProfilePhoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|max:2048',
        ]);

        // Handle validation errors
        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors(), 422);
        }

        try {
            $user = $request->user();

            // Check if there's an existing file and delete it
            if ($user->profile_image) {
                deleteFile($user->profile_image);
            }

            $image = uploadFile($request->file('image'), "uploads/profile", "do_spaces");

            $user->update([
                'profile_image'  => $image
            ]);

            return $this->sendResponse([], "Profile updated successfully", 201);
        } catch (\Exception $e) {
            sendToLog($e);

            return $this->sendError(serviceDownMessage(), [], 500);
        }
    }
}