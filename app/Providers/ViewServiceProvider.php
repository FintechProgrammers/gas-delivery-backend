<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share the logged-in user details with all views
        View::composer('*', function ($view) {
            if (auth('admin')->check()) {
                $view->with('loggedInUser', auth('admin')->user());
            } elseif (auth()->check()) {
                $view->with('loggedInUser', auth()->user());
            }
        });
    }
}
