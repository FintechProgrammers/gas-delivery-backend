<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\PhoneNumberTokenJob;
use App\Models\PhoneVerificationCode;
use App\Traits\RecursiveActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class VerifyPhoneVerificationController extends Controller
{
    use RecursiveActions;

    function requestCode(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'phone_number' => 'required|string|unique:users,phone_number',
            ]);

            // Handle validation errors
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $token = $this->generatePhoneToken($request->phone_number);

            PhoneNumberTokenJob::dispatch($request->phone_number, $token);

            return $this->sendResponse([], "Phone number verification code send successful", Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->sendError(serviceDownMessage(), [], Response::HTTP_SERVICE_UNAVAILABLE);
        }
    }

    function verifyCode(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'phone_number' => 'required|string|unique:users,phone_number',
            ]);

            // Handle validation errors
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $token = PhoneVerificationCode::where('token', $request->token)
                ->where('phone_number', $request->phone_number)
                ->where('created_at', '>', now()->subSeconds(3600))
                ->first();

            if (!$token) {
                return $this->sendError('Invalid token', Response::HTTP_UNAUTHORIZED);
            }

            return $this->sendResponse([], "Phone number verification successful", Response::HTTP_OK);
        } catch (\Exception $e) {
            sendToLog($e);
            return $this->sendError(serviceDownMessage(), [], Response::HTTP_SERVICE_UNAVAILABLE);
        }
    }
}