<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request)
    {
        try {
            $user =  $request->user();

            $user->update([
                'phone_number' => $request->phone_number,
            ]);

            $user->userProfile->update([
                'country_code'  => $request->country,
                'address'       => $request->address,
                'city'          => $request->city,
                'state'         => $request->state,
            ]);

            return $this->sendResponse([], 'Profile updated successfully.');
        } catch (\Exception $e) {
            logger($e);

            return response()->json(['success' => false, 'message' => serviceDownMessage()], 500);
        }
    }

    function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|max:2048',
        ]);

        // Handle validation errors
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
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

            return response()->json(['success' => false, 'message' => 'Successfully uploaded.']);
        } catch (\Exception $e) {
            logger($e);

            return response()->json(['success' => false, 'message' => serviceDownMessage(), 500]);
        }
    }

    function updatePassword(UpdatePasswordRequest $request)
    {
        try {
            $user = $request->user();

            // check if current password is valid
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json(['success' => false, 'message' => 'Invalid current password'], 401);
            }

            $user->update([
                'password'  => Hash::make($request->password)
            ]);

            return response()->json(['success' => true, 'message' => 'Password updated successfully']);
        } catch (\Exception $e) {
            logger($e);

            return response()->json(['success' => false, 'message' => serviceDownMessage()], 500);
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
