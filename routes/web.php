<?php

use App\Http\Controllers\NijaPayController;
use App\Http\Controllers\ProvidusController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('queue-work', function () {
    Illuminate\Support\Facades\Artisan::call('queue:work', ['--stop-when-empty' => true]);
})->name('queue.work');

Route::get('/deposit/simulation', function () {

    $nijaPaySerice = new \App\Services\NijaPay();

    $data = [
        'recipient_account_number' => '9000058477',
        'amount' => 200 * 100
    ];

    $response = $nijaPaySerice->getStimuteDeposit($data);

    dd($response);
});

Route::prefix('webhook/response')->group(function () {
    Route::post('/providus', [ProvidusController::class, 'webhook']);
    Route::post('/nijapay', [NijaPayController::class, 'webhook']);
});

require __DIR__ . '/auth.php';
