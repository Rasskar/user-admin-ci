<?php

namespace App\Services\Profiles;

use App\DTO\Profiles\ProfileUpdateDTO;
use App\Entities\ProfileEntity;
use App\Models\ProfileModel;
use App\Models\UserModel;
use App\Models\GroupModel;
use App\Services\Files\FileSaveService;
use CodeIgniter\Database\Exceptions\DataException;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\Shield\Entities\Group;
use CodeIgniter\Shield\Entities\User;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Throwable;

class ProfileUpdateService
{
    /**
     * @param ProfileUpdateDTO $dto
     */
    public function __construct(
        protected ProfileUpdateDTO $dto
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
            $this->updateUser();
            $this->updateGroup();
            $this->updateProfile();

            $db->transCommit();
        } catch (Throwable $exception) {
            $db->transRollback();
            throw $exception;
        }
    }

    /**
     * @return void
     */
    private function updateUser(): void
    {
        $userModel = new UserModel();

        /* @var User $user */
        $user = $userModel->find($this->dto->userId);

        if (!$user) {
            throw new NotFoundResourceException("User not found.");
        }

        $user->username = $this->dto->userName;

        if (!$userModel->update($user->id, $user->toArray())) {
            throw new DataException("Error updating user information");
        }
    }

    /**
     * @return void
     * @throws \ReflectionException
     */
    private function updateGroup(): void
    {
        $groupModel = new GroupModel();

        /* @var Group $group */
        $group = $groupModel->where('user_id', $this->dto->userId)->first();

        if (!$group) {
            throw new NotFoundResourceException("User group not found.");
        }

        $group->group = $this->dto->userRole;

        if (!$groupModel->update($group->id, $group->toArray())) {
            throw new DataException("Error updating user group");
        }
    }

    /**
     * @return void
     * @throws \ReflectionException
     */
    private function updateProfile(): void
    {
        $profileModel = new ProfileModel();

        /* @var ProfileEntity $user */
        $profile = $profileModel->where('user_id', $this->dto->userId)->first();

        if (!$profile) {
            throw new NotFoundResourceException("User profile not found.");
        }

        $filePath = (new FileSaveService($this->dto->profileImage, 'profilePhoto', $profile->user_id))->save();

        if (!empty($filePath)) {
            $profile->photo_link = $filePath;
        }

        $profile->fill([
            'first_name' => $this->dto->firstName,
            'last_name' => $this->dto->lastName,
            'description' => $this->dto->description,
        ]);

        if(!$profileModel->update($profile->id, $profile->toArray())) {
            throw new DataException("Error saving profile");
        }
    }
}