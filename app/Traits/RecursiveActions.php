<?php

namespace App\Traits;

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

        UserOtp::create([
            'user_id' => $userId,
            'token' => $otp,
            'created_at' => Carbon::now(),
            'purpose' => $purpose,
        ]);

        return $otp;
    }
}
