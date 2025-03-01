<?php

namespace App\Services\Profiles;

use App\DTO\Profiles\ProfileCreateDTO;
use App\Models\ProfileModel;
use App\Models\UserModel;
use App\Modules\Infrastructure\Services\Logs\DatabaseLogService;
use App\Services\Files\FileSaveService;
use App\Services\Logs\LogModelService;
use Carbon\Carbon;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Shield\Authentication\Authenticators\Session;
use CodeIgniter\Shield\Models\GroupModel;
use CodeIgniter\Shield\Models\UserIdentityModel;
use Exception;

class ProfileCreateService
{
    /**
     * @var int|null
     */
    protected int|null $userId = null;

    /**
     * @var LogModelService
     */
    protected LogModelService $logService;

    /**
     * @param ProfileCreateDTO $dto
     */
    public function __construct(
        protected ProfileCreateDTO $dto
    )
    {
        $this->logService = new LogModelService(new DatabaseLogService());
    }

    /**
     * @return void
     * @throws \ReflectionException
     */
    public function execute(): void
    {
        $db = db_connect();
        $db->transBegin();

        try {
            $this->createUser();
            $this->createUserIdentity();
            $this->createUserGroup();
            $this->updateProfile();

            $db->transCommit();
        } catch (Exception $exception) {
            $db->transRollback();
            throw $exception;
        }
    }

    /**
     * @return int|null
     */
    public function getUserId(): int|null
    {
        return $this->userId;
    }

    /**
     * @return void
     */
    private function createUser(): void
    {
        $user = new UserModel();
        $attributes = [
            'username' => $this->dto->userName,
            'last_active' => Carbon::now(),
            'active' => 1,
        ];
        $this->userId = $user->insert($attributes);

        if (!$this->userId) {
            throw new DatabaseException('Error creating user.');
        }

        $this->logService->logCreate(
            auth()->id(),
            UserModel::class,
            $this->userId,
            $attributes
        );
    }

    /**
     * @return void
     * @throws \ReflectionException
     */
    private function createUserIdentity(): void
    {
        $userIdentity = new UserIdentityModel();
        $passwords = service('passwords');
        $attributes = [
            'user_id' => $this->userId,
            'type' => Session::ID_TYPE_EMAIL_PASSWORD,
            'secret' => $this->dto->userEmail,
            'secret2' => $passwords->hash($this->dto->userPassword),
        ];
        $userIdentityId = $userIdentity->insert($attributes);

        if (!$userIdentityId) {
            throw new DatabaseException('Error creating user identity');
        }

        $this->logService->logCreate(
            auth()->id(),
            UserIdentityModel::class,
            $userIdentityId,
            $attributes
        );
    }

    /**
     * @return void
     * @throws \ReflectionException
     */
    private function createUserGroup(): void
    {
        $groupUser = new GroupModel();
        $attributes = [
            'user_id' => $this->userId,
            'group'   => $this->dto->userRole,
        ];
        $groupUserId = $groupUser->insert($attributes);

        if (!$groupUserId) {
            throw new DatabaseException('Error creating user group');
        }

        $this->logService->logCreate(
            auth()->id(),
            GroupModel::class,
            $groupUserId,
            $attributes
        );
    }

    /**
     * @return void
     * @throws \ReflectionException
     */
    private function updateProfile(): void
    {
        $profileModel = new ProfileModel();
        $profile = $profileModel->where('user_id', $this->userId)->first();
        $oldAttribute = $profile->toArray();

        $filePath = (new FileSaveService($this->dto->profileImage, 'profilePhoto', $this->userId))->save();

        if (!empty($filePath)) {
            $profile->photo_link = $filePath;
        }

        $profile->fill([
            'first_name' => $this->dto->firstName,
            'last_name' => $this->dto->lastName,
            'description' => $this->dto->description,
        ]);

        if (!$profileModel->update($profile->id, $profile->toArray())) {
            throw new DatabaseException("Error creating profile");
        }

        $this->logService->logUpdate(
            auth()->id(),
            ProfileModel::class,
            $profile->id,
            $oldAttribute,
            $profile->toArray()
        );
    }
}