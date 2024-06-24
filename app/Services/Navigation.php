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
                'icon'  => 'bx bx-home',
                'hasPermission' => true
            ],
            (object) [
                'name'      => 'Users',
                'icon'      => 'fe fe-users',
                'routes'    => ['admin.users.index'],
                'hasPermission' => true,
                'subMenu'   => (object) [
                    (object) [
                        'name'  => 'Administrators',
                        'route' => 'admin.admins.index',
                        'hasPermission' => true
                    ],
                    (object) [
                        'name'  => 'All Users',
                        'route' => 'admin.users.index',
                        'hasPermission' => true
                    ],
                ]
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
