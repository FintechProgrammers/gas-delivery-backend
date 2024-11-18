<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotTransactionPinRequest;
use App\Http\Requests\TransactionPinRequest;
use App\Http\Requests\UpdateTransactionPinRequest;
use App\Models\UserOtp;
use App\Notifications\TransactionTokenReset;
use App\Traits\RecursiveActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class TransactionPinController extends Controller
{
    use RecursiveActions;

    function setTransactionPin(TransactionPinRequest $request)
    {
        try {
            $user = $request->user();

            $user->update([
                'transaction_pin' => Hash::make($request->transaction_pin)
            ]);

            return $this->sendResponse([], "Transaction pin set successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            sendToLog($e);

            return $this->sendError(serviceDownMessage(), [], 500);
        }
    }

    function updateTransactionPin(UpdateTransactionPinRequest $request)
    {
        try {
            $user = $request->user();

            // check if current password is valid
            if (!Hash::check($request->current_pin, $user->transaction_pin)) {
                return response()->json(['success' => false, 'message' => 'Invalid current pin'], 401);
            }

            $user->update([
                'transaction_pin' => Hash::make($request->transaction_pin)
            ]);

            return $this->sendResponse([], "Transaction pin updated successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            sendToLog($e);

            return $this->sendError(serviceDownMessage(), [], 500);
        }
    }

    function requestResetToken(Request $request)
    {
        try {

            $user = $request->user();

            $code = $this->generateUserOtp($user->id, "pin_reset");

            $user->notify(new TransactionTokenReset($code));

            return $this->sendResponse([], "Reset token sent successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            sendToLog($e);

            return $this->sendError(serviceDownMessage(), [], 500);
        }
    }

    function resetTransactionPin(ForgotTransactionPinRequest $request)
    {
        try {
            $user = $request->user();

            $phoneToken = UserOtp::where('token', $request->token)
                ->where('purpose', 'pin_reset')
                ->where('created_at', '>', now()->subSeconds(3600))
                ->first();

            if (!$phoneToken) {
                return $this->sendError('Invalid token', Response::HTTP_UNAUTHORIZED);
            }

            $user->update([
                'transaction_pin' => Hash::make($request->transaction_pin)
            ]);

            $phoneToken->delete();

            return $this->sendResponse([], "Transaction pin updated successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            sendToLog($e);

            return $this->sendError(serviceDownMessage(), [], 500);
        }
    }
}
