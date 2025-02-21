<?php

namespace App\Services\Profiles;

use App\Models\ProfileModel;
use App\Models\UserModel;
use Exception;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class ProfileDataService
{
    public function __construct(
        protected $userId
    )
    {
    }

    public function getData(): array
    {
        try {
            $user = (new UserModel())->find($this->userId);
            $profile = (new ProfileModel())->where('user_id', $this->userId)->first();

            if (!$user || !$profile) {
                throw new NotFoundResourceException("Ошибка получения данных");
            }

            return [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->getEmail(),
                'first_name' => $profile->first_name,
                'last_name' => $profile->last_name,
                'description' => $profile->description,
                'photo_link' => $profile->getPhoto()
            ];
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}