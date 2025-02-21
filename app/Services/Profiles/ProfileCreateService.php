<?php

namespace App\Services\Profiles;

use App\DTO\Profiles\ProfileCreateDTO;
use App\Models\ProfileModel;
use App\Models\UserModel;
use App\Services\Files\FileSaveService;
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
     * @param ProfileCreateDTO $dto
     */
    public function __construct(
        protected ProfileCreateDTO $dto
    )
    {
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
        $this->userId = $user->insert([
            'username' => $this->dto->userName,
            'last_active' => Carbon::now(),
            'active' => 1,
        ]);

        if (!$this->userId) {
            throw new DatabaseException('Ошибка создания пользователя.');
        }
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

        if (!$userIdentity->insert($attributes)) {
            throw new DatabaseException('Ошибка создания идентификации пользователя');
        }
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

        if (!$groupUser->insert($attributes)) {
            throw new DatabaseException('Ошибка создания группы пользователя');
        }
    }

    /**
     * @return void
     * @throws \ReflectionException
     */
    private function updateProfile(): void
    {
        $profileModel = new ProfileModel();
        $profile = $profileModel->where('user_id', $this->userId)->first();

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
            throw new DatabaseException("Ошибка создания профиля");
        }
    }
}