<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use App\Models\UserOtp;
use App\Models\UserToken;
use App\Notifications\ForgotPassword;
use App\Notifications\ForgotPasswordNotification;
use App\Traits\RecursiveActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class RecoverPasswordController extends Controller
{
    use RecursiveActions;

    function forgotPassword(ForgotPasswordRequest $request)
    {
        try {
            $user = User::where('phone_number', $request->phone_number)->first();

            if (!$user) {
                return $this->sendError("Invalid phone number", [], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $code = $this->generateUserOtp($user->id, "password_reset");

            $user->notify(new ForgotPasswordNotification($code));

            return $this->sendResponse([], "Password reset token sent successfully");
        } catch (\Exception $e) {
            sendToLog($e);

            return $this->sendError(serviceDownMessage(), [], 500);
        }
    }

    function resetPassword(ResetPasswordRequest $request)
    {
        try {
            $validated = (object) $request->validated();

            $user = User::where('phone_number', $request->phone_number)->first();

            if (!$user) {
                return $this->sendError("Invalid phone number", [], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            // get the verification token
            $token = UserOtp::where('token', $validated->token)
                ->where('user_id', $user->id)
                ->where('purpose', 'password_reset')
                // ->where('created_at', '>', now()->subSeconds(60))
                ->first();

            if (!$token) {
                return $this->sendError('Invalid token', Response::HTTP_UNAUTHORIZED);
            }

            $user->update(['password' => Hash::make($validated->password)]);

            $token->delete();

            return $this->sendResponse(null, "Password Reset successfully");
        } catch (\Exception $e) {

            sendToLog($e);

            return $this->sendError(serviceDownMessage(), [], 500);
        }
    }
}
