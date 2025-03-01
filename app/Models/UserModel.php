<?php

namespace App\Models;

use App\Modules\Infrastructure\Services\Logs\DatabaseLogService;
use App\Services\Logs\LogModelService;
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
        $userId = $data['id'] ?? ($data['data']['id'] ?? null);

        if ($userId) {
            $profileModel = new ProfileModel();
            $attributes = ['user_id' => $userId];
            $profileId = $profileModel->insert($attributes, true);

            (new LogModelService(new DatabaseLogService()))->logCreate($userId, ProfileModel::class, $profileId, $attributes);
        }

        return $data;
    }
}
