<?php

namespace App\Models;

use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;

class User extends ShieldUserModel
{
    /**
     * @param array $data
     * @return array
     * @throws \ReflectionException
     */
    protected function afterInsert(array $data): array
    {
        $userID = $data['id'] ?? null;

        if ($userID) {
            $profile = new \App\Models\Profile();
            $profileID = $profile->insert(['user_id' => $userID], true);

            if ($profileID) {
                $this->update($userID, ['profile_id' => $profileID]);
            }
        }

        return $data;
    }
}
