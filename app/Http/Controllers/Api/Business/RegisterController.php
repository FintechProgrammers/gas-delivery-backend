<?php

namespace App\Http\Controllers\Api\Business;

use App\Http\Controllers\Controller;
use App\Http\Requests\BusinessRegistration;
use App\Http\Resources\UserResource;
use App\Jobs\Mail\VerifyEmailJob;
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

    function __invoke(BusinessRegistration $request)
    {
        $validated = (object) $request->validated();

        if (!empty($request->email) && User::where('email', $request->email)->exists()) {
            return $this->sendError("Email address already taken", [], 422);
        }

        if (User::where('business_name', $request->business_name)->exists()) {
            return $this->sendError("Business name already exists. Please choose a different name.", [], 422);
        }

        if (User::where('phone_number', $validated->phone_number)->exists()) {
            return $this->sendError("Phone number already taken", [], 422);
        }

        try {
            DB::beginTransaction();

            $phoneToken = PhoneVerificationCode::where('phone_number', $validated->phone_number)
                ->where('is_verified', true)
                ->first();

            if (!$phoneToken) {
                return $this->sendError("Kindly verify your phone number before proceeding", [], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            // $photoUrl = null;

            // if ($request->hasFile('photo')) {
            //     $photoUrl = uploadFile($request->file('photo'), 'users', 'do_spaces');
            // }

            $user = User::create([
                'business_name' => $validated->business_name,
                // 'email'  => $validated->email,
                'phone_number' => $validated->phone_number,
                'is_business' => true,
                'account_type' => 'BUSINESS',
                'phone_number_verified_at' => now(),
                'profile_image' => $request->photo
            ]);

            UserInfo::create([
                'user_id' => $user->id,
                'address' => $request->address,
                'longitude' => $request->longitude,
                'latitude' => $request->latitude,
                'dpr_number' => $request->dpr_number,
                'bussiness_type' => $request->bussiness_type
            ]);

            // $mailData = [
            //     'first_name' => ucfirst($user->first_name),
            //     'email' => $user->email,
            //     'account_type' => "BUSINESS",
            //     'code' => $this->generateUserOtp($user->id, 'email_verification'),
            // ];

            // // sent Onboarding
            // dispatch(new VerifyEmailJob($mailData));

            $token = $user->createToken('auth_token')->accessToken;

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
