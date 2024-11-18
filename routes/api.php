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
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\Rider\LoginController as RiderLoginController;
use App\Http\Controllers\Api\Rider\OrderRequestController;
use App\Http\Controllers\Api\Rider\RegisterController as RiderRegisterController;
use App\Http\Controllers\Api\TransactionPinController;
use App\Http\Controllers\Api\VendorController;
use App\Http\Controllers\Api\VerifyPhoneNumberController;
use App\Http\Controllers\Api\VerifyPhoneVerificationController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

Route::prefix('auth')->group(function () {
    Route::post('/register', RegisterController::class);
    Route::post('/login', LoginController::class);

    Route::post('/validate/token', [VerifyPhoneNumberController::class, 'validateToken'])->middleware('auth:api');
    Route::post('/send/token', [VerifyPhoneNumberController::class, 'resentLoginToken'])->middleware('auth:api');

    Route::prefix('business')->group(function () {
        Route::controller(VerifyPhoneVerificationController::class)->prefix('phone')->group(function () {
            Route::post('token/request', 'requestCode');
            Route::post('verify', 'verifyCode');
        });

        Route::post('/register', BusinessRegisterController::class);
        Route::post('/login', BusinessLoginController::class);
    });

    Route::prefix('rider')->group(function () {
        Route::controller(VerifyPhoneVerificationController::class)->prefix('phone')->group(function () {
            Route::post('token/request', 'requestCode');
            Route::post('verify', 'verifyCode');
        });

        Route::post('/register', RiderRegisterController::class);
        Route::post('/login', RiderLoginController::class);
    });
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
        Route::post('/password/update', 'updatePassword');
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

    Route::controller(OrderController::class)->prefix('orders')->group(function () {
        Route::get('', 'index');
        Route::post('', 'placeOrder');
        Route::get('/show/{order}', 'orderDetails');
        Route::post('/rider/request', 'requestRider');
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

    Route::prefix('rider')->group(function () {
        Route::controller(OrderRequestController::class)->prefix('orders')->group(function () {
            Route::get('', 'index');
            Route::post('/accept/{order}', 'acceptOrder');
            Route::post('/reject/{order}', 'rejectOrder');
        });
    });

    Route::controller(TransactionPinController::class)->prefix('transaction/pin')->group(function () {
        Route::post('set', 'setTransactionPin');
        Route::post('update', 'updateTransactionPin');
        Route::post('reset/token', 'requestResetToken');
        Route::post('reset', 'resetTransactionPin');
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
