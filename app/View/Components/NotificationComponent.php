<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class NotificationComponent extends Component
{
    public $notifications;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $user = Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::user();

        $this->notifications = $user->unreadNotifications;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.notification-component');
    }
}
