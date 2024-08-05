<?php

use App\Http\Controllers\Admin\AdministrativeUserController;
use App\Http\Controllers\Admin\Auth\AdminForgotPasswordController;
use App\Http\Controllers\Admin\Auth\AdminResetPasswordController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DeliveryController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SupportController;
use App\Http\Controllers\Admin\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::middleware('admin.guest')->group(function () {
    Route::get('', [LoginController::class, 'index'])->name('login');
    Route::post('', [LoginController::class, 'login'])->name('login.post');

    Route::controller(AdminForgotPasswordController::class)->prefix('forgot-password')->name('forgot.password.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::post('', 'store')->name('store');
    });

    Route::controller(AdminResetPasswordController::class)->prefix('reset-password')->name('reset.password.')->group(function () {
        Route::get('/{token}', 'index')->name('index');
        Route::post('', 'reset')->name('store');
    });
});

Route::middleware('admin.auth')->group(function () {
    Route::controller(DashboardController::class)->prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('', 'index')->name('index');
    });

    Route::controller(UserManagementController::class)->prefix('users')->name('users.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('/filter', 'filter')->name('filter');
        Route::post('', 'store')->name('store');
        Route::get('/create', 'create')->name('create');
        Route::get('/show/{user}', 'show')->name('show');
        Route::post('/suspend/{user}', 'suspend')->name('suspend');
        Route::post('/activate/{user}', 'activate')->name('activate');
        Route::post('/delete/{user}', 'destroy')->name('delete');
    });

    Route::controller(AdministrativeUserController::class)->prefix('admins')->name('admins.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('/filter', 'filter')->name('filter');
        Route::post('', 'store')->name('store');
        Route::get('/create', 'create')->name('create');
        Route::get('/show/{admin}', 'show')->name('show');
        Route::get('/edit/{admin}', 'edit')->name('edit');
        Route::post('/update/{admin}', 'update')->name('update');
        Route::post('/suspend/{admin}', 'suspend')->name('suspend');
        Route::post('/activate/{admin}', 'activate')->name('activate');
        Route::post('/delete/{admin}', 'destroy')->name('delete');
    });

    Route::controller(RoleController::class)->prefix('roles')->name('roles.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('', 'store')->name('store');
        Route::get('/edit/{role},', 'edit')->name('edit');
        Route::post('/update/{role},', 'update')->name('update');
        Route::delete('/{role}', 'destroy')->name('delete');
    });

    Route::controller(SupportController::class)->prefix('support')->name('support.')->group(function () {

        Route::prefix('tickets')->name('tickets.')->group(function () {
            Route::get('', 'index')->name('index');
            Route::get('/filter', 'tickets')->name('filter');
            Route::post('', 'store')->name('store');
            Route::get('/create', 'create')->name('create');
            Route::get('/show/{ticket}', 'show')->name('show');
            Route::get('/replies/{ticket}', 'getReplies')->name('replies');
            Route::post('/replies/{ticket}', 'replyTicket')->name('replies.store');
            Route::post('/reply/{ticket}', 'replyTicket')->name('reply');
            Route::post('/update/{ticket}', 'update')->name('update');
            Route::post('/close/{ticket}', 'closeTicket')->name('close');
            Route::post('/delete/{ticket}', 'deleteTicket')->name('delete');
        });

        Route::prefix('subjects')->name('subjects.')->group(function () {
            Route::get('', 'subjects')->name('index');
            Route::get('/filter', 'subjectsTable')->name('filter');
            Route::post('', 'storeSubject')->name('store');
            Route::get('/create', 'createSubject')->name('create');
            Route::get('/show/{subject}', 'editSubject')->name('edit');
            Route::post('/update/{subject}', 'updateSubject')->name('update');
            Route::post('/delete/{subject}', 'deleteSubject')->name('delete');
        });
    });

    Route::controller(ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::post('update-profile-image', 'updateProfile')->name('update.image');
        Route::post('update-password', 'updatePassword')->name('update.password');
        Route::get('logout', 'logout')->name('logout');
    });

    Route::controller(BannerController::class)->prefix('banner')->name('banner.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('/filter', 'filter')->name('filter');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{banner}', 'edit')->name('edit');
        Route::patch('/update/{banner}', 'update')->name('update');
        Route::post('/publish/{banner}', 'publish')->name('publish');
        Route::post('/unpublish/{banner}', 'unpublish')->name('unpublish');
        Route::post('/delete/{banner}', 'destroy')->name('delete');
    });

    Route::controller(DeliveryController::class)->prefix('delivery')->name('delivery.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('filter', 'filter')->name('filter');
    });

    Route::controller(OrderController::class)->prefix('order')->name('order.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/filter', 'filter')->name('filter');
    });

    Route::get('update-countries', [CountryController::class, 'updateCountriesTableWithFlags']);
});
