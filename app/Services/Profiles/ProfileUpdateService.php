<?php

namespace App\Services\Profiles;

use App\DTO\Profiles\ProfileUpdateDto;
use App\Entities\ProfileEntity;
use App\Models\ProfileModel;
use App\Models\UserModel;
use App\Models\GroupModel;
use CodeIgniter\Database\Exceptions\DataException;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\Shield\Entities\Group;
use CodeIgniter\Shield\Entities\User;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Throwable;

class ProfileUpdateService
{
    /**
     * @param ProfileUpdateDto $dto
     */
    public function __construct(
        protected ProfileUpdateDto $dto
    )
    {
    }

    /**
     * @param int $userId
     * @return void
     * @throws Throwable
     * @throws \ReflectionException
     */
    public function updateProfile(int $userId): void
    {
        $db = db_connect();
        $db->transBegin();

        try {
            $userModel = new UserModel();

            /* @var User $user */
            $user = $userModel->find($userId);

            if (!$user) {
                throw new NotFoundResourceException("Пользователь не найден");
            }

            $profileModel = new ProfileModel();

            /* @var ProfileEntity $user */
            $profile = $profileModel->where('user_id', $userId)->first();

            if (!$profile) {
                throw new NotFoundResourceException("Профиль пользователя не найден.");
            }

            $groupModel = new GroupModel();

            /* @var Group $group */
            $group = $groupModel->where('user_id', $userId)->first();

            if (!$group) {
                throw new NotFoundResourceException("Группа пользователя не найдена.");
            }

            $this->setProfileImage($profile);

            $profile->fill([
                'first_name' => $this->dto->firstName,
                'last_name' => $this->dto->lastName,
                'description' => $this->dto->description,
            ]);

            $user->username = $this->dto->userName;
            $group->group = $this->dto->userRole;


            if (!$userModel->update($user->id, $user->toArray())
                || !$profileModel->update($profile->id, $profile->toArray())
                || !$groupModel->update($group->id, $group->toArray())) {
                throw new DataException("Ошибка сохранения профиля");
            }

            $db->transCommit();
        } catch (Throwable $exception) {
            $db->transRollback();
            throw $exception;
        }
    }

    /**
     * @param ProfileEntity $profile
     * @return void
     */
    private function setProfileImage(ProfileEntity $profile): void
    {
        if ($this->dto->profileImage instanceof UploadedFile
            && $this->dto->profileImage->isValid()
            && !$this->dto->profileImage->hasMoved()) {
            $uploadPath = WRITEPATH . 'uploads/profilePhoto';

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $newFileName = $profile->user_id . '_' . time() . '.' . $this->dto->profileImage->getExtension();
            $this->dto->profileImage->move($uploadPath, $newFileName);

            $profile->photo_link = 'profilePhoto/' . $newFileName;
        }
    }
}