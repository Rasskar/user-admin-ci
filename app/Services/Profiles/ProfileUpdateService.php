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
            throw new NotFoundResourceException("Пользователь не найден");
        }

        $user->username = $this->dto->userName;

        if (!$userModel->update($user->id, $user->toArray())) {
            throw new DataException("Ошибка обновления информации пользователя");
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
            throw new NotFoundResourceException("Группа пользователя не найдена.");
        }

        $group->group = $this->dto->userRole;

        if (!$groupModel->update($group->id, $group->toArray())) {
            throw new DataException("Ошибка обновления группы пользователя");
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
            throw new NotFoundResourceException("Профиль пользователя не найден.");
        }

        $this->setProfileImage($profile);

        $profile->fill([
            'first_name' => $this->dto->firstName,
            'last_name' => $this->dto->lastName,
            'description' => $this->dto->description,
        ]);

        if(!$profileModel->update($profile->id, $profile->toArray())) {
            throw new DataException("Ошибка сохранения профиля");
        }
    }

    /**
     * @param ProfileEntity $profile
     * @return void
     */
    private function setProfileImage(ProfileEntity $profile): void
    {
        if (!$this->dto->profileImage instanceof UploadedFile
            || !$this->dto->profileImage->isValid()
            || $this->dto->profileImage->hasMoved()) {
            return;
        }

        $uploadPath = WRITEPATH . 'uploads/profilePhoto';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $newFileName = $profile->user_id . '_' . time() . '.' . $this->dto->profileImage->getExtension();
        $this->dto->profileImage->move($uploadPath, $newFileName);

        $profile->photo_link = 'profilePhoto/' . $newFileName;
    }
}