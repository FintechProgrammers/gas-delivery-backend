<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Notifications\VeryPhoneNumber;
use App\Traits\RecursiveActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Twilio\Rest\Client;

class RegisterController extends Controller
{
    use RecursiveActions;

    function __invoke(RegisterRequest $request)
    {
        try {
            $validated = $request->validated();

            DB::beginTransaction();

            $parent = null;

            // Check if referral code is not empty then assign parent to the person registering.
            if ($request->filled('referral_code')) {
                // get user that has code
                $parent = User::where('referral_code', $validated['referral_code'])->value('id');

                if (empty($parent)) {
                    return $this->sendError('The referral code you entered is not valid.');
                }
            }

            $user = User::create([
                'first_name'    => $validated['first_name'],
                'last_name'     => $validated['last_name'],
                'email'         => $validated['email'],
                'phone_number'  => formatPhoneNumber($validated['phone_number']),
                'date_of_birth' => $validated['date_of_birth'],
                'parent_id'     => $parent,
                'is_business'   => false,
                'account_type' => 'CUSTOMER',
            ]);

            $code = $this->generateUserOtp($user->id, "phone_number_verification");

            $user->notify(new VeryPhoneNumber($code));

            $token = $user->createToken('authToken')->accessToken;

            $user = new UserResource($user);

            $data = [
                'user' => $user,
                'token' => $token,
            ];

            // send phone number verification token
            DB::commit();

            return $this->sendResponse($data, "Registered successfully.", 201);
        } catch (\Exception $e) {
            DB::rollBack();

            sendToLog($e);

            return $this->sendError(serviceDownMessage(), [], 500);
        }
    }
}
