<?php

use App\Http\Controllers\NijaPayController;
use App\Http\Controllers\ProvidusController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    dd(Hash::make("test1234"));
    return view('welcome');
});

Route::get('queue-work', function () {
    Illuminate\Support\Facades\Artisan::call('queue:work', ['--stop-when-empty' => true]);
})->name('queue.work');

Route::get('/deposit/simulation', function (Request $request) {

    $nijaPaySerice = new \App\Services\NijaPay();

    $data = [
        'recipient_account_number' => $request->account_number,
        'amount' => 200 * 100
    ];

    $response = $nijaPaySerice->getStimuteDeposit($data);

    dd($response);
});

Route::get('test-email', function () {
    $email = "ndamjoh@gmail.com";

    $code = rand(100000, 999999);
    \Illuminate\Support\Facades\Notification::route('mail', $email)
        ->notify(new \App\Notifications\EmailVerificationToken($code));

    return "Email sent";
});

Route::prefix('webhook/response')->group(function () {
    Route::post('/providus', [ProvidusController::class, 'webhook']);
    Route::post('/nijapay', [NijaPayController::class, 'webhook']);
});

require __DIR__ . '/auth.php';
