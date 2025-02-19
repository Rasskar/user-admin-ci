<?php

namespace App\Models;

use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;

class UserModel extends ShieldUserModel
{
    protected $afterInsert = ['saveEmailIdentity', 'createProfile'];

    /**
     * Создаёт профиль пользователя после успешной регистрации
     *
     * @param array $data
     * @return array
     */
    protected function createProfile(array $data): array
    {
        $userID = $data['id'] ?? ($data['data']['id'] ?? null);

        if ($userID) {
            $profileModel = new ProfileModel();
            $profileModel->insert(['user_id' => $userID], true);
        }

        return $data;
    }
}
