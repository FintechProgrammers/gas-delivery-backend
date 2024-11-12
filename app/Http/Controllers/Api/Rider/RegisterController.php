<?php

namespace App\Http\Controllers\Api\Rider;

use App\Http\Controllers\Controller;
use App\Http\Requests\RiderRegistrationRequest;
use App\Http\Resources\UserResource;
use App\Models\PhoneVerificationCode;
use App\Models\User;
use App\Models\UserInfo;
use App\Traits\RecursiveActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    use RecursiveActions;

    function __invoke(RiderRegistrationRequest $request)
    {
        $validated = (object) $request->validated();

        try {
            DB::beginTransaction();

            // validate phone number otp
            $phoneToken = PhoneVerificationCode::where('token', $validated->token)
                ->where('phone_number', $validated->phone_number)
                ->where('created_at', '>', now()->subSeconds(3600))
                ->first();

            if (!$phoneToken) {
                return $this->sendError('Invalid token', Response::HTTP_UNAUTHORIZED);
            }

            $user = User::create([
                'first_name' => $validated->first_name,
                'last_name' => $validated->last_name,
                'email'  => $validated->email,
                'phone_number' => $validated->phone_number,
                'password' => Hash::make($validated->password),
                'is_business' => false,
                'account_type' => 'RIDER',
                'phone_number_verified_at' => now(),
            ]);

            UserInfo::create([
                'user_id' => $user->id,
            ]);


            $token = $user->createToken('auth_token')->accessToken;

            $user = new UserResource($user);

            $data = [
                'user' => $user,
                'token' => $token,
            ];

            $phoneToken->delete();

            DB::commit();

            return $this->sendResponse($data, "Registration success successfully.", 201);
        } catch (\Exception $e) {
            DB::rollBack();
            sendToLog($e);

            return $this->sendError(serviceDownMessage(), [], 500);
        }
    }
}
