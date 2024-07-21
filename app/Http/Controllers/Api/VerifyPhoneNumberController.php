<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\VerifyAccountRequest;
use App\Http\Resources\UserResource;
use App\Models\UserOtp;
use App\Notifications\AuthToken;
use App\Notifications\VeryPhoneNumber;
use App\Traits\RecursiveActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class VerifyPhoneNumberController extends Controller
{
    use RecursiveActions;

    function requestToken(Request $request)
    {
        try {

            $user = $request->user();

            $code = $this->generateUserOtp($user->id, "phone_number_verification");

            $user->notify(new VeryPhoneNumber($code));

            return $this->sendResponse([], "Verification token sent successfully.");
        } catch (\Exception $e) {

            sendToLog($e);

            return $this->sendError(serviceDownMessage(), [], 500);
        }
    }

    function verifyPhoneNumber(VerifyAccountRequest $request)
    {
        try {

            $validated = (object) $request->validated();

            $user = $request->user();

            // get the verification token
            $token = UserOtp::where('token', $validated->token)
                ->where('user_id', $user->id)
                ->where('purpose', 'phone_number_verification')
                ->where('created_at', '>', now()->subSeconds(3600))
                ->first();

            if (!$token) {
                return $this->sendError('Invalid token', Response::HTTP_UNAUTHORIZED);
            }

            if ($user->email_verified_at) {
                return $this->sendError('Your phone number is already verified', Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $user->update(['phone_number_verified_at' => now()]);

            $token->delete();

            return $this->sendResponse(null, "Verified successfully.");
        } catch (\Exception $e) {
            DB::rollBack();

            sendToLog($e);

            return $this->sendError(serviceDownMessage(), [], 500);
        }
    }

    function resentLoginToken(Request $request)
    {
        try {

            $user = $request->user();

            $code = $this->generateUserOtp($user->id, "auth_otp");

            $user->notify(new AuthToken($code));

            return $this->sendResponse([], "Auth token sent successfully.");
        } catch (\Exception $e) {

            sendToLog($e);

            return $this->sendError(serviceDownMessage(), [], 500);
        }
    }

    function validateToken(VerifyAccountRequest $request)
    {
        try {

            $validated = (object) $request->validated();

            $user = $request->user();

            // get the verification token
            $token = UserOtp::where('token', $validated->token)
                ->where('user_id', $user->id)
                ->where('purpose', 'auth_otp')
                ->where('created_at', '>', now()->subSeconds(60))
                ->first();

            if (!$token) {
                return $this->sendError('Invalid token', Response::HTTP_UNAUTHORIZED);
            }

            $token->delete();

            $user = new UserResource($user);

            return $this->sendResponse($user, "Successful");
        } catch (\Exception $e) {
            DB::rollBack();

            sendToLog($e);

            return $this->sendError(serviceDownMessage(), [], 500);
        }
    }
}
