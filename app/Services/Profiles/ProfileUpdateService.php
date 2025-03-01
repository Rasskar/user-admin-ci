<?php

namespace App\Services\Profiles;

use App\DTO\Profiles\ProfileUpdateDTO;
use App\Entities\ProfileEntity;
use App\Models\ProfileModel;
use App\Models\UserModel;
use App\Models\GroupModel;
use App\Modules\Infrastructure\Services\Logs\DatabaseLogService;
use App\Services\Files\FileSaveService;
use App\Services\Logs\LogModelService;
use CodeIgniter\Database\Exceptions\DataException;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\Shield\Entities\Group;
use CodeIgniter\Shield\Entities\User;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Throwable;

class ProfileUpdateService
{
    /**
     * @var LogModelService
     */
    protected LogModelService $logService;

    /**
     * @param ProfileUpdateDTO $dto
     */
    public function __construct(
        protected ProfileUpdateDTO $dto
    )
    {
        $this->logService = new LogModelService(new DatabaseLogService());
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

        $oldAttributes = $user->toArray();
        $newAttributes = ['username' => $this->dto->userName];

        $changedData = array_diff_assoc($newAttributes, $oldAttributes);

        if (!empty($changedData)) {
            if (!$userModel->update($user->id, $changedData)) {
                throw new DataException("Error updating user information");
            }

            $this->logService->logUpdate(auth()->id(), UserModel::class, $user->id, $oldAttributes, $changedData);
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

        $oldAttributes = $group->toArray();
        $newAttributes = ['group' => $this->dto->userRole];

        $changedData = array_diff_assoc($newAttributes, $oldAttributes);

        if (!empty($changedData)) {
            if (!$groupModel->update($group->id, $changedData)) {
                throw new DataException("Error updating user group");
            }

            $this->logService->logUpdate(auth()->id(), GroupModel::class, $group->id, $oldAttributes, $changedData);
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

        $oldAttributes = $profile->toArray();
        $newAttributes = [
            'first_name'  => $this->dto->firstName,
            'last_name'   => $this->dto->lastName,
            'description' => $this->dto->description,
        ];

        $filePath = (new FileSaveService($this->dto->profileImage, 'profilePhoto', $profile->user_id))->save();

        if (!empty($filePath)) {
            $newAttributes['photo_link'] = $filePath;
        }

        $changedData = array_diff_assoc($newAttributes, $oldAttributes);

        if (!empty($changedData)) {
            if (!$profileModel->update($profile->id, $changedData)) {
                throw new DataException("Error saving profile");
            }

            $this->logService->logUpdate(auth()->id(), ProfileModel::class, $profile->id, $oldAttributes, $changedData);
        }
    }
}