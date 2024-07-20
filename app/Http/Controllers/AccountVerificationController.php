<?php

namespace App\Http\Controllers;

use App\Http\Requests\VerifyAccountRequest;
use App\Jobs\Mail\VerifyEmailJob;
use App\Models\UserOtp;
use App\Traits\RecursiveActions;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AccountVerificationController extends Controller
{
    use RecursiveActions;

    function verifyEmail(VerifyAccountRequest $request)
    {
        try {
            $validated = (object) $request->validated();

            $user = $request->user();

            // get the verification token
            $token = UserOtp::where('token', $validated->token)
                ->where('user_id', $user->id)
                ->where('purpose', 'email_verification')
                ->where('created_at', '>', now()->subSeconds(3600))
                ->first();

            if (!$token) {
                return $this->sendError('Invalid token', Response::HTTP_UNAUTHORIZED);
            }

            if ($user->email_verified_at) {
                return $this->sendError('Your email is already verified', Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $user->update(['email_verified_at' => now()]);

            $token->delete();

            \App\Jobs\Mail\WelcomeMailJob::dispatch([
                'first_name' => ucfirst($user->first_name),
                'email' => $user->email,
                'account_type' => $user->account_type
            ]);

            return $this->sendResponse(null, "Verification Token has been sent successfully.");
        } catch (\Exception $e) {

            sendToLog($e);

            return $this->sendError(serviceDownMessage(), [], 500);
        }
    }

    function sentEmailToken(Request $request)
    {
        try {
            $user = $request->user();

            $mailData = [
                'first_name' => ucfirst($user->first_name),
                'email' => $user->email,
                'account_type' => $user->account_type,
                'code' => $this->generateUserOtp($user->id, 'email_verification'),
            ];

            VerifyEmailJob::dispatch($mailData);

            return $this->sendResponse(null, "Verification Token has been sent successfully.");
        } catch (\Exception $e) {

            sendToLog($e);

            return $this->sendError(serviceDownMessage(), [], 500);
        }
    }
}
