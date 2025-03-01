<?php

namespace App\Helpers;

class MenuHelper
{
    /**
     * @return array[]
     */
    public static function getMenuItems(): array
    {
        return [
            [
                'title' => lang('Navigation.dashboard'),
                'path' => '/',
                'icon' => "<i class='bx bxs-dashboard' ></i>",
            ],
            [
                'title' => lang('Navigation.profile'),
                'path' => '/profile/' . auth()->id(),
                'icon' => "<i class='bx bxs-user'></i>",
            ],
            [
                'title' => lang('Navigation.profiles'),
                'path' => '/profiles',
                'icon' => "<i class='bx bx-list-ul'></i>",
                'roles' => ['admin']
            ],
            [
                'title' => lang('Navigation.create'),
                'path' => '/profile/add',
                'icon' => "<i class='bx bx-plus'></i>",
                'roles' => ['admin']
            ],
            [
                'title' => lang('Navigation.history'),
                'path' => '/history',
                'icon' => "<i class='bx bx-history'></i>",
                'roles' => ['admin']
            ]
        ];
    }

}