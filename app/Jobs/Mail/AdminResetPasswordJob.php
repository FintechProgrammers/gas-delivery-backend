<?php

namespace App\Jobs\Mail;

use App\Mail\AdminResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AdminResetPasswordJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public $data)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $body = new AdminResetPassword($this->data['token']);

        $email = $this->data['email'];

        $sent = sendMailByDriver('mailgun', $email, $body);
        logger('first');

        // Send mail via send grid driver if mailgun fails
        if (!$sent) {
            logger('smtp');
            sendMailByDriver('smtp', $email, $body);
        }
    }
}
