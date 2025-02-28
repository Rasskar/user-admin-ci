<?php

namespace App\Services\Profiles;

use App\Models\UserModel;
use Exception;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class ProfileToggleStatusService
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
     * @throws Exception
     */
    public function execute(): void
    {
        try {
            $userModel = new UserModel();
            $user = $userModel->find($this->userId);

            if (!$user) {
                throw new NotFoundResourceException("User not found.");
            }

            $user->active = !$user->active;
            $userModel->save($user);
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}