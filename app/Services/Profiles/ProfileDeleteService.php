<?php

namespace App\Services\Profiles;

use App\Models\ProfileModel;
use App\Models\UserModel;
use CodeIgniter\Shield\Models\GroupModel;
use CodeIgniter\Shield\Models\UserIdentityModel;
use Exception;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Throwable;

class ProfileDeleteService
{
    /**
     * @param int $userId
     */
    public function __construct(
        protected int $userId
    )
    {
    }

    /**
     * @return void
     * @throws Throwable
     */
    public function execute(): void
    {
        $db = db_connect();
        $db->transBegin();

        try {
            $userModel = new UserModel();
            $profileModel = new ProfileModel();
            $identityModel = new UserIdentityModel();
            $groupModel = new GroupModel();

            if (!$userModel->find($this->userId)) {
                throw new NotFoundResourceException("User not found.");
            }

            $identityModel->where('user_id', $this->userId)->delete();
            $groupModel->where('user_id', $this->userId)->delete();
            $profileModel->where('user_id', $this->userId)->delete();
            $userModel->delete($this->userId);

            $db->transCommit();
        } catch (Exception $exception) {
            $db->transRollback();
            throw $exception;
        }
    }

}