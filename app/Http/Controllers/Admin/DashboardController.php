<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rank;
use App\Models\Service;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    function index()
    {
        $data['stats'] = $this->statistics();

        return view('admin.dashboard.index', $data);
    }

    function statistics()
    {

        $totalUsers = User::count();

        $productsCount = 0;

        $tickets = Ticket::count();

        $ranks = 0;

        return  [
            (object) [
                'title' => 'Total Users',
                'value' => $totalUsers,
                'color' => 'text-bg-info',
                'icon' => 'bi bi-people-fill',
                'link' => null,
            ],
            (object) [
                'title' => 'Active Users',
                'value' => 0,
                'color' => 'text-bg-success',
                'icon' => 'bi bi-people-fill',
                'link' => null,
            ],
            (object) [
                'title' => 'Inactive Users',
                'value' => 0,
                'color' => 'text-bg-warning',
                'icon' => 'bi bi-people-fill',
                'link' => null,
            ],
            (object) [
                'title' => 'Products',
                'value' => $productsCount,
                'color' => 'text-bg-success',
                'icon' => 'las la-sitemap',
                'link' => null,
            ],
            (object) [
                'title' => 'Ranks',
                'value' => $ranks,
                'color' => 'text-bg-info',
                'icon' => 'las la-medal',
                'link' => null,
            ],
            (object) [
                'title' => 'Support Tickets',
                'value' => $tickets,
                'color' => 'text-bg-dark',
                'icon' => 'las la-headset',
                'link' => null,
            ],
        ];
    }
}
