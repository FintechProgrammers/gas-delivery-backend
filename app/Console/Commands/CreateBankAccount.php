<?php

namespace App\Console\Commands;

use App\Models\PaystackCustomer;
use App\Models\User;
use App\Services\Paystack;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CreateBankAccount extends Command
{
    protected $signature = 'app:create-bank-account';
    protected $description = 'Create Paystack bank accounts for users without one';

    public function handle()
    {
        // Use chunking to process users in batches
        User::where('account_type', 'CUSTOMER')
            ->doesntHave('bankAccount')
            ->chunkById(200, function ($users) {
                $paystack = new Paystack();

                foreach ($users as $user) {
                    try {
                        $payload = [
                            'email' => $user->email,
                            'first_name' => $user->first_name,
                            'last_name' => $user->last_name,
                            'phone_number' => $user->phone_number,
                        ];

                        $response = $paystack->assignBankAccount($payload);

                        if ($response['success']) {
                            PaystackCustomer::firstOrCreate(
                                ['use_id' => $user->id],
                                ['use_id' => $user->id]
                            );

                            $this->info("Created bank account for user ID: {$user->id}");
                        } else {
                            Log::warning("Failed to create bank account for user {$user->id}", [
                                'response' => $response
                            ]);
                        }
                    } catch (\Exception $e) {
                        Log::error("Error processing user {$user->id}", [
                            'error' => $e->getMessage()
                        ]);
                        continue;
                    }
                }
            });

        $this->info('Bank account creation process completed.');
    }
}
