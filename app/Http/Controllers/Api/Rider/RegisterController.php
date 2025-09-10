<?php

namespace App\Http\Controllers\Api\Rider;

use App\Http\Controllers\Controller;
use App\Http\Requests\RiderRegistrationRequest;
use App\Http\Resources\UserResource;
use App\Jobs\PhoneNumberTokenJob;
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

            $user = User::create([
                'first_name' => $validated->first_name,
                'last_name' => $validated->last_name,
                'date_of_birth' => $validated->date_of_birth,
                'email'  => $validated->email,
                'phone_number' => $validated->phone_number,
                'password' => Hash::make($validated->password),
                'date_of_birth' => $validated->date_of_birth,
                'is_business' => false,
                'account_type' => 'RIDER',
                'phone_number_verified_at' => now(),
            ]);

            UserInfo::create([
                'user_id' => $user->id,
            ]);

            $token = $user->createToken('auth_token')->accessToken;

            $code = $this->generateUserOtp($user->id, "email_verification");

            $user->notify(new \App\Notifications\EmailVerificationToken($code));

            // $otp = $this->generatePhoneToken($request->phone_number);

            // PhoneNumberTokenJob::dispatch($request->phone_number, $otp);

            $user = new UserResource($user);

            $data = [
                'user' => $user,
                'token' => $token,
            ];

            DB::commit();

            return $this->sendResponse($data, "Registration success successfully.", 201);
        } catch (\Exception $e) {
            DB::rollBack();
            sendToLog($e);

            return $this->sendError(serviceDownMessage(), [], 500);
        }
    }
}
