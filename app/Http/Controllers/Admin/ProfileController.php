<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    function index()
    {
        $data['user'] = Auth::guard('admin')->user();

        return view('admin.profile.index', $data);
    }

    function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        // Handle validation errors
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $user = Auth::guard('admin')->user();

            // Check if there's an existing file and delete it
            if ($user->profile_image) {
                deleteFile($user->profile_image);
            }

            $image = uploadFile($request->file('image'), "uploads/profile", "do_spaces");

            Admin::where('id', $user->id)->update([
                'profile_image'  => $image
            ]);

            return response()->json(['success' => false, 'message' => 'Successfully uploaded.']);
        } catch (\Exception $e) {
            logger($e);

            return response()->json(['success' => false, 'message' => serviceDownMessage()],500);
        }
    }

    function updatePassword(UpdatePasswordRequest $request)
    {
        try {
            $user = Auth::guard('admin')->user();

            // check if current password is valid
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json(['success' => false, 'message' => 'Invalid current password'], 401);
            }

            Admin::where('id', $user->id)->update([
                'password'  => Hash::make($request->password)
            ]);

            return response()->json(['success' => true, 'message' => 'Password updated successfully']);
        } catch (\Exception $e) {
            logger($e);

            return response()->json(['success' => false, 'message' => serviceDownMessage()],500);
        }
    }

    function logout()
    {
        Auth::guard('admin')->logout();

        return back()->with('success', 'Logged out successfully');
    }
}
