<?php

namespace App\Jobs;

use App\Notifications\VeryPhoneNumber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
        $notifiable = new class {
            public $phone;
            public function routeNotificationForWhatsApp()
            {
                return $this->phone;
            }
        };

        $notifiable->phone = $this->phoneNumber;

        $notifiable->notify(new VeryPhoneNumber($this->token));
    }
}
