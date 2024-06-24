<?php

namespace App\View\Components\User\Dashboard;

use App\Models\Ticket;
use App\Models\UserSubscription;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class StatsComponent extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $user = Auth::user();

        $ticketsCount = Ticket::where('user_id', $user->id)->count();
        $packages = UserSubscription::where('user_id', $user->id)->where('is_active', true)->count();

        $stats = [
            (object) [
                'title' => 'Active Subscriptions',
                'value' => number_format($packages),
                'color' => 'bg-success',
                'icon' => 'bx bx-cube-alt',
                'show' => true,
            ],
            (object) [
                'title' => 'Total Subscriptions',
                'value' => number_format($packages),
                'color' => 'bg-primary',
                'icon' => 'bx bx-doughnut-chart',
                'show' => true,
            ],
            (object) [
                'title' => 'Tickets',
                'value' => number_format($ticketsCount),
                'color' => 'bg-warning',
                'icon' => 'bx bx-support',
                'show' => true,
            ],
            (object) [
                'title' => 'Sales',
                'value' => '$4,000',
                'color' => 'bg-primary',
                'icon' => 'bx bx-line-chart',
                'show' => Auth::user()->is_ambassador ?? false,
            ],
            (object) [
                'title' => 'Withdrawals',
                'value' => '$12,000',
                'color' => 'bg-danger',
                'icon' => 'bx bx-log-out',
                'show' => Auth::user()->is_ambassador ?? false,
            ],
            (object) [
                'title' => 'Total Earnings',
                'value' => '$34,000',
                'color' => 'bg-success',
                'icon' => 'bx bx-wallet',
                'show' => Auth::user()->is_ambassador ?? false,
            ],
        ];

        $data['stats'] = $stats;

        return view('components.user.dashboard.stats-component', $data);
    }
}
