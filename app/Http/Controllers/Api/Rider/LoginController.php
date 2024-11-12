<?php

namespace App\Http\Controllers\Api\Rider;

use App\Http\Controllers\Controller;
use App\Http\Requests\BusinessLoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    function __invoke(BusinessLoginRequest $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();

            $user = User::where('phone_number', $validated['phone_number'])->where('account_type', 'RIDER')->first();

            if (!$user || !Hash::check($validated['password'], $user->password)) {
                return $this->sendError("Invalid login credentials", [], 422);
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
