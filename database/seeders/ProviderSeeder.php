<?php

namespace Database\Seeders;

use App\Models\Provider;
use Illuminate\Database\Seeder;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $providers = [
            [
                'name' => 'Providus',
                'short_name' => 'providus',
                'is_default' => false,
                'has_transaction' => false,
                'has_account' => false,
            ],
            [
                'name' => '9japay MFB',
                'short_name' => '9japay',
                'is_default' => false,
                'has_transaction' => false,
                'has_account' => false,
            ],
        ];

        foreach ($providers as $data) {
            Provider::updateOrCreate(
                ['short_name' => $data['short_name']], // Prevent duplicates
                $data
            );
        }
    }
}
