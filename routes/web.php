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

// Route::get('test-rider-request', function () {

//     $order = \App\Models\GasOrder::whereUuid('7f62db2d-38ea-44d3-8882-d8e4ebc76319')->first();

//     $handler = new \App\Jobs\AssignRiderToOrder($order);

//     $handler->handle();

//     dd("done");
// });

Route::get('test-sms', function () {
    $bulkSms = new \App\Services\BulkSms();

    $data = [
        'to' => '2349167615132',
        'message' => 'This is a test message from FaastGas.',
    ];

    $response = $bulkSms->sendSms($data);

    dd($response);
});

Route::prefix('webhook/response')->group(function () {
    Route::post('/providus', [ProvidusController::class, 'webhook']);
    Route::post('/nijapay', [NijaPayController::class, 'webhook']);
});

require __DIR__ . '/auth.php';
