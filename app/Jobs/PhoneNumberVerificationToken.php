<?php

namespace App\Jobs;

use App\Services\BulkSms;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PhoneNumberVerificationToken implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The verification token.
     *
     * @var string
     */
    protected $token;

    /**
     * The phone number to send the verification token to.
     *
     * @var string
     */
    protected $phoneNumber;

    /**
     * Create a new notification instance.
     *
     * @param string $token
     * @param string $phoneNumber
     */
    public function __construct(string $token, string $phoneNumber)
    {
        $this->token = $token;

        // Remove + sign if present
        $phoneNumber = str_replace('+', '', $phoneNumber);

        // Ensure number starts with 234 (Nigerian country code)
        if (str_starts_with($phoneNumber, '0')) {
            // Convert 0xxx to 234xxx
            $phoneNumber = '234' . substr($phoneNumber, 1);
        } elseif (!str_starts_with($phoneNumber, '234')) {
            // If it doesn't start with 234 or 0, prepend 234
            $phoneNumber = '234' . $phoneNumber;
        }

        $this->phoneNumber = $phoneNumber;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $bulkSms = new BulkSms();

        $message = "Your FaastGas verification code is: {$this->token}. This code will expire in 10 minutes.";

        $data = [
            'to' => $this->phoneNumber,
            'from' => 'FaastGas',
            'message' => $message,
        ];

        $bulkSms->sendSms($data);
    }
}
