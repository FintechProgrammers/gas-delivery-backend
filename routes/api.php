<?php

use App\Http\Controllers\AccountVerificationController;
use App\Http\Controllers\Api\Business\GasPricingController;
use App\Http\Controllers\Api\Business\LoginController as BusinessLoginController;
use App\Http\Controllers\Api\Business\RegisterController as BusinessRegisterController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\DeliveryAddressController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\RegisterController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

Route::prefix('auth')->group(function () {
    Route::post('/register', RegisterController::class);
    Route::post('/login', LoginController::class);

    Route::prefix('business')->group(function () {
        Route::post('/register', BusinessRegisterController::class);
        Route::post('/login', BusinessLoginController::class);
    });
});

Route::middleware(['auth:api'])->group(function () {
    Route::controller(DeliveryAddressController::class)->prefix('delivery/address')->group(function () {
        Route::get('', 'index');
        Route::post('', 'store');
        Route::get('/{address}', 'show');
        Route::patch('/{address}', 'update');
        Route::delete('/{address}', 'destroy');
    });

    Route::controller(ProfileController::class)->prefix('profile')->group(function () {
        Route::get('', 'index');
        Route::patch('', 'update');
        Route::patch('/update/photo', 'updateProfilePhoto');
        Route::patch('/business/update', 'updateBusinessProfile');
    });

    Route::prefix('business')->group(function () {
        Route::controller(GasPricingController::class)->prefix('pricing')->group(function () {
            Route::get('/', 'index');
            Route::post('/', 'store');
        });
    });

    Route::controller(AccountVerificationController::class)->prefix('account')->group(function () {
        Route::post('/email/verify', 'verifyEmail');
        Route::post('/email/token', 'sentEmailToken');
    });
});

Route::controller(CountryController::class)->prefix('countries')->group(function () {
    Route::get('', 'index');
    Route::get('update/flags', 'updateCountriesTableWithFlags');
});

Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'Specified page not found.',
        'data' => [],
    ], Response::HTTP_BAD_REQUEST);
});
