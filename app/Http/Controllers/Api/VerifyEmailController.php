<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserOtp;
use App\Traits\RecursiveActions;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    use RecursiveActions;

    public function sendVerificationEmail(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->email_verified_at) {
                return $this->sendError('Your email is already verified', 422);
            }

            $code = $this->generateUserOtp($user->id, "email_verification");

            $user->notify(new \App\Notifications\EmailVerificationToken($code));

            return $this->sendResponse([], "Verification email sent successfully.");
        } catch (\Exception $e) {

            sendToLog($e);

            return $this->sendError(serviceDownMessage(), [], 500);
        }
    }

    public function verifyEmail(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $user = $request->user();

        if ($user->email_verified_at) {
            return response()->json([
                'message' => 'Email already verified.',
            ], 200);
        }

        // Assuming you have a method to verify the token
        if ($this->isValidToken($user, $request->token)) {
            $user->email_verified_at = now();
            $user->save();

            return response()->json([
                'message' => 'Email verified successfully.',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Invalid verification token.',
            ], 400);
        }
    }

    protected function isValidToken($user, $token)
    {
        return UserOtp::where('user_id', $user->id)
            ->where('token', $token)
            ->where('purpose', 'email_verification')
            ->where('created_at', '>', now()->subSeconds(3600))
            ->exists();
    }
}
