<?php

namespace App\Models;

use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;

class User extends ShieldUserModel
{
    protected $table         = 'users';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['email', 'username', 'password_hash', 'profile_id'];
    protected $useTimestamps = true;

    // Указываем, что у пользователя есть профиль
    public function profile()
    {
        return $this->hasOne(Profile::class, 'id', 'profile_id');
    }

    // Автоматически создаём профиль при регистрации
    protected function beforeInsert(array $data)
    {
        $profileModel = new Profile();
        $profileID = $profileModel->insert([], true);

        if ($profileID) {
            $data['data']['profile_id'] = $profileID;
        }

        return $data;
    }
}
