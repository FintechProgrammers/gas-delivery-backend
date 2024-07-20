<?php

namespace App\Http\Controllers\Api\Business;

use App\Http\Controllers\Controller;
use App\Http\Requests\BusinessRegistration;
use App\Http\Resources\UserResource;
use App\Jobs\Mail\VerifyEmailJob;
use App\Models\User;
use App\Traits\RecursiveActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    use RecursiveActions;

    function __invoke(BusinessRegistration $request)
    {
        $validated = (object) $request->validated();

        try {
            DB::beginTransaction();

            $user = User::create([
                'email'  => $validated->email,
                'password' => Hash::make($validated->password),
                'first_name' => $validated->first_name,
                'last_name' => $validated->last_name,
                'is_business' => true
            ]);

            $mailData = [
                'first_name' => ucfirst($user->first_name),
                'email' => $user->email,
                'account_type' => "BUSINESS",
                'code' => $this->generateUserOtp($user->id, 'email_verification'),
            ];

            // sent Onboarding
            dispatch(new VerifyEmailJob($mailData));

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
