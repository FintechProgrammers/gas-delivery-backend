<?php

namespace App\Jobs;

use App\Notifications\VeryPhoneNumber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Notifications\Notifiable;

class PhoneNumberTokenJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected $phoneNumber, protected $token)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $notifiable = new class {
                use Notifiable;

                public $phone;

                public function routeNotificationForWhatsApp()
                {
                    return $this->phone;
                }
            };

            $notifiable->phone = $this->phoneNumber;

            // Send the notification
            $notifiable->notify(new VeryPhoneNumber($this->token));
        } catch (\Exception $e) {
            logger($e);
        }
    }
}
