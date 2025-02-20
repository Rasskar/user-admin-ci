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
                'title' => 'Dashboard',
                'path' => '/',
                'icon' => "<i class='bx bxs-dashboard' ></i>",
            ],
            [
                'title' => 'Мой профиль',
                'path' => '/profile/' . auth()->id(),
                'icon' => "<i class='bx bxs-user'></i>",
            ],
            [
                'title' => 'Все профили',
                'path' => '/profiles',
                'icon' => "<i class='bx bx-list-ul'></i>",
                'roles' => ['admin']
            ],
        ];
    }

}