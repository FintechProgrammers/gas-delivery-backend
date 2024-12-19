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
            return $this->sendError("Email address already taken", [], 422);
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

            $vehicle_image = null;

            $driver_license = null;

            $driver_license_back = null;

            if ($request->hasFile('vehicle_image')) {
                $vehicle_image = uploadFile($request->file('vehicle_image'), 'uploads/vehicle', 'do_spaces');
            }

            if ($request->hasFile('driver_license')) {
                $driver_license = uploadFile($request->file('driver_license'), 'uploads/license', 'do_spaces');
            }

            if ($request->hasFile('driver_license_back')) {
                $driver_license_back = uploadFile($request->file('driver_license_back'), 'uploads/license', 'do_spaces');
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

            $vehicalInformation = [
                'vehicle_image' => $vehicle_image,
                'vehicle_colour' => $request->filled('vehicle_colour') ? $request->vehicle_colour : null,
                'vehicle_number' => $request->filled('vehicle_number') ?  $request->vehicle_number : null,
            ];

            UserInfo::create([
                'user_id' => $user->id,
                'address' => $request->address,
                'driver_license' => $driver_license,
                'driver_license_back' => $driver_license_back,
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
