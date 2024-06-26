<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    function __invoke(LoginRequest $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();

            $user = User::where('phone_number', $validated['phone_number'])->where('is_business', false)->first();

            if (!$user) {
                return $this->sendError("Invalid login credentials", [], 404);
            }

            // check if user account is active
            if (in_array($user->status, ['blocked', 'suspended'], true)) {
                return $this->sendError("Your account has been restricted. Please contact our support team for assistance.", [], 401);
            }

            $token = $user->createToken('auth_token')->accessToken;

            $user = new UserResource($user);

            $data = [
                'user' => $user,
                'token' => $token,
            ];

            // send number verification token

            DB::commit();

            return $this->sendResponse($data, "Login successfully.", 201);
        } catch (\Exception $e) {
            DB::rollBack();

            sendToLog($e);

            return $this->sendError(serviceDownMessage(), [], 500);
        }
    }
}
