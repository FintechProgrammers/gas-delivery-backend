<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;
use App\Models\User;
use App\Models\Admin;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function ($user, string $token) {
            $baseAdminUrl = route('admin.reset.password.index', ['token' => $token]);
            $baseUserUrl = route('password.reset', ['token' => $token]);

            return match (true) {
                $user instanceof Admin => $baseAdminUrl . '?email=' . urlencode($user->email),
                $user instanceof User => $baseUserUrl . '?email=' . urlencode($user->email),
                // other user types
                default => throw new \Exception("Invalid user type"),
            };
        });
    }
}
