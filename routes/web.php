<?php

use App\Events\DriverRquest;
use App\Http\Controllers\NijaPayController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProvidusController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\User\AcademyController;
use App\Http\Controllers\User\AmbassedorController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\ReportController;
use App\Http\Controllers\User\SalesController;
use App\Http\Controllers\User\ServiceController;
use App\Http\Controllers\User\SubscriptionController;
use App\Http\Controllers\User\SupportController;
use App\Http\Controllers\WithdrawalController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('queue-work', function () {
    Illuminate\Support\Facades\Artisan::call('queue:work', ['--stop-when-empty' => true]);
})->name('queue.work');

Route::prefix('webhook/response')->group(function () {
    Route::post('/providus', [ProvidusController::class, 'webhook']);
    Route::post('/nijapay', [NijaPayController::class, 'webhook']);
});

require __DIR__ . '/auth.php';
