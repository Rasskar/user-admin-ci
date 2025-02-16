<?php

namespace App\Database\Seeds;

use App\Models\User;
use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $users = auth()->getProvider();

        if ($users->where('email', env('ADMIN_EMAIL'))->first()) {
            echo "Admin уже существует \n";
            return;
        }

        $admin = new User([
            'email'    => env('ADMIN_EMAIL'),
            'username' => env('ADMIN_USERNAME', 'admin'),
            'password' => env('ADMIN_PASSWORD'),
            'active'   => 1
        ]);

        $users->save($admin);

        $admin = $users->where('email', env('ADMIN_EMAIL'))->first();
        $admin->addGroup('admin');

        echo "Успешно создан Admin. \n";
    }
}
