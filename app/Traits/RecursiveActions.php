<?php

namespace App\Traits;

use App\Models\PhoneVerificationCode;
use App\Models\UserOtp;
use Carbon\Carbon;

trait RecursiveActions
{

    function generateOtpCode()
    {
        // Generate a random 4-digit OTP
        return mt_rand(1111, 9999);
    }

    /**
     *
     * function to generate otp for user
     *
     * @return integer $otp
     *
     */
    public function generateUserOtp($userId, $purpose)
    {
        $otp = $this->generateOtpCode();

        UserOtp::updateOrCreate(
            [
                'user_id' => $userId,
                'purpose' => $purpose,
            ],
            [
                'token' => $otp,
                'created_at' => Carbon::now(),
            ]
        );

        return $otp;
    }

    public function generatePhoneToken($phoneNumber)
    {
        $otp = $this->generateOtpCode();

        PhoneVerificationCode::updateOrCreate(
            [
                'phone_number' => $phoneNumber,
            ],
            [
                'token' => $otp,
                'created_at' => Carbon::now(),
            ]
        );

        return $otp;
    }
}
