<?php

use App\Http\Controllers\AccountVerificationController;
use App\Http\Controllers\Api\Business\GasPricingController;
use App\Http\Controllers\Api\Business\LoginController as BusinessLoginController;
use App\Http\Controllers\Api\Business\RegisterController as BusinessRegisterController;
use App\Http\Controllers\Api\Business\SettingsController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\DeliveryAddressController;
use App\Http\Controllers\Api\GasPriceController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\VendorController;
use App\Http\Controllers\Api\VerifyPhoneNumberController;
use App\Http\Controllers\Api\VerifyPhoneVerificationController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

Route::prefix('auth')->group(function () {
    Route::post('/register', RegisterController::class);
    Route::post('/login', LoginController::class);

    Route::prefix('business')->group(function () {
        Route::controller(VerifyPhoneVerificationController::class)->prefix('phone')->group(function () {
            Route::post('token/request', 'requestCode');
            Route::post('verify', 'verifyCode');
        });

        Route::post('/register', BusinessRegisterController::class);
        Route::post('/login', BusinessLoginController::class);
    });

    Route::post('/validate/token', [VerifyPhoneNumberController::class, 'validateToken'])->middleware('auth:api');
    Route::post('/send/token', [VerifyPhoneNumberController::class, 'resentLoginToken'])->middleware('auth:api');
});

Route::middleware(['auth:api'])->group(function () {

    Route::controller(VerifyPhoneNumberController::class)->prefix('phone-number')->group(function () {
        Route::post('/token/request', 'requestToken');
        Route::post('/verify', 'verifyPhoneNumber');
    });

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

    Route::prefix('gas')->group(function () {
        Route::controller(GasPriceController::class)->group(function () {
            Route::get('/price/list', 'index');
        });

        Route::controller(VendorController::class)->prefix('vendors')->group(function () {
            Route::get('/', 'index');
            Route::get('/{user}', 'show');
        });
    });

    Route::prefix('business')->group(function () {
        Route::controller(GasPricingController::class)->prefix('pricing')->group(function () {
            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::post('/price-per-kg', 'setPricePerKg');
            Route::patch('/update/{gasPrice}', 'update');
            Route::delete('/delete/{gasPrice}', 'destroy');
        });

        Route::controller(SettingsController::class)->prefix('settings')->group(function () {
            Route::post('opening/days', 'openingDays');
            Route::post('availability', 'toggleAvailability');
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
