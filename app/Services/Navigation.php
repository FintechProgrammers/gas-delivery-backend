<?php

namespace App\Services;


class Navigation
{
    public static function adminNavigation()
    {

        return [
            (object) [
                'name'  => 'Dashboard',
                'route' => 'admin.dashboard.index',
                'icon'  => 'iconoir-home-simple',
                'hasPermission' => true
            ],
            (object) [
                'name'  => 'Customers',
                'route' => 'admin.customer.index',
                'icon'  => 'iconoir-user-cart',
                'hasPermission' => true
            ],
            (object) [
                'name'  => 'Riders',
                'route' => 'admin.rider.index',
                'icon'  => 'iconoir-cycling',
                'hasPermission' => true
            ],
            (object) [
                'name'  => 'Vendors',
                'route' => 'admin.users.index',
                'icon'  => 'iconoir-user-bag',
                'hasPermission' => true
            ],
            (object) [
                'name'  => 'Administrators',
                'route' => 'admin.admins.index',
                'icon'  => 'iconoir-home-simple',
                'hasPermission' => true
            ],
            (object) [
                'name'  => 'Orders',
                'route' => 'admin.order.index',
                'icon'  => 'iconoir-cart-alt',
                'hasPermission' => true
            ],
            (object) [
                'name'  => 'Transactions',
                'route' => 'admin.transactions.index',
                'icon'  => 'iconoir-coins',
                'hasPermission' => true
            ],
            (object) [
                'name'      => 'Supports',
                'icon'      => 'las la-headset',
                'routes'    => ['admin.support.subjects.index', 'admin.support.tickets.index'],
                'hasPermission' => true,
                'subMenu'   => (object) [
                    (object) [
                        'name'  => 'Subjects',
                        'route' => 'admin.support.subjects.index',
                        'hasPermission' => true
                    ],
                    (object) [
                        'name'  => 'Tickets',
                        'route' => 'admin.support.tickets.index',
                        'hasPermission' => true
                    ],
                ]
            ],
            (object) [
                'name'  => 'Role Management',
                'route' => 'admin.roles.index',
                'icon'  => 'las la-bezier-curve',
                'hasPermission' => true
            ],
            (object) [
                'name'  => 'Settings',
                'route' => 'admin.roles.index',
                'icon'  => 'iconoir-tools',
                'hasPermission' => true
            ],
        ];
    }

    public static function clientNavigation()
    {
        return [
            (object) [
                'name'  => 'Dashboard',
                'route' => 'dashboard',
                'icon'  => 'bx bx-home',
                'hasPermission' => true
            ]
        ];
    }
}
