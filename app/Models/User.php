<?php

namespace App\Models;

use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;

class User extends ShieldUserModel
{
    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var string[]
     */
    protected $allowedFields = [
        'email',
        'username',
        'password_hash',
        'profile_id'
    ];

    /**
     * @var bool
     */
    protected $useTimestamps = true;

    /**
     * @return Profile
     */
    public function profile(): Profile
    {
        return $this->hasOne(Profile::class, 'id', 'profile_id');
    }

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
