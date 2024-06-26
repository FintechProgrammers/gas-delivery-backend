<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
