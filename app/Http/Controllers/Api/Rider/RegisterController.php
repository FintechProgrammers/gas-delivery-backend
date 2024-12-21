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

        if (User::where('email', $validated->email)->exists()) {
            return $this->sendError("Email address already taken", [], 422);
        }

        if (User::where('phone_number', $validated->phone_number)->exists()) {
            return $this->sendError("phone number already taken", [], 422);
        }


        try {
            DB::beginTransaction();

            // validate phone number otp
            // $phoneToken = PhoneVerificationCode::where('token', $validated->token)
            //     ->where('phone_number', $validated->phone_number)
            //     ->where('created_at', '>', now()->subSeconds(3600))
            //     ->first();

            // if (!$phoneToken) {
            //     return $this->sendError('Invalid token', Response::HTTP_UNAUTHORIZED);
            // }

            $user = User::create([
                'first_name' => $validated->first_name,
                'last_name' => $validated->last_name,
                'date_of_birth' => $validated->date_of_birth,
                'email'  => $validated->email,
                'phone_number' => $validated->phone_number,
                'password' => Hash::make($validated->password),
                'is_business' => false,
                'account_type' => 'RIDER',
                'phone_number_verified_at' => now(),
            ]);

            $vehicalInformation = [
                'vehicle_image' => $request->filled('vehicle_image') ? $request->vehicle_image : null,
                'vehicle_colour' => $request->filled('vehicle_colour') ? $request->vehicle_colour : null,
                'vehicle_number' => $request->filled('vehicle_number') ?  $request->vehicle_number : null,
            ];

            UserInfo::create([
                'user_id' => $user->id,
                'address' => $request->address,
                'vehical_details' => json_encode($vehicalInformation),
            ]);

            $token = $user->createToken('auth_token')->accessToken;

            $user = new UserResource($user);

            $data = [
                'user' => $user,
                'token' => $token,
            ];

            // $phoneToken->delete();

            DB::commit();

            return $this->sendResponse($data, "Registration success successfully.", 201);
        } catch (\Exception $e) {
            DB::rollBack();
            sendToLog($e);

            return $this->sendError(serviceDownMessage(), [], 500);
        }
    }
}
