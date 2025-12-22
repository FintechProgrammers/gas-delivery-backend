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
