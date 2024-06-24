<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class AdminForgotPasswordController extends Controller
{

    function index()
    {
        return view('admin.auth.forgot-password');
    }
    /**
     * Handle the incoming request.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
        ]);

        // Handle validation errors
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {

            $status = Password::broker('admins')->sendResetLink(
                $request->only('email')
            );

            return $status === Password::RESET_LINK_SENT
                ? response()->json(['message' => __($status)])
                : response()->json(['message' => __($status)], 400);
        } catch (\Exception $e) {
            logger($e);

            return response()->json(['success' => false, 'message' => serviceDownMessage()],500);
        }
    }
}
