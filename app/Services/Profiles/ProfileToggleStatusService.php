<?php

namespace App\Services\Profiles;

use App\Models\UserModel;
use App\Modules\Infrastructure\Services\Logs\DatabaseLogService;
use App\Services\Logs\LogModelService;
use Exception;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class ProfileToggleStatusService
{
    /**
     * @var LogModelService
     */
    protected LogModelService $logService;

    /**
     * @param int $userId
     */
    public function __construct(
        protected int $userId
    )
    {
        $this->logService = new LogModelService(new DatabaseLogService());
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

            $oldAttributes = $user->toArray();
            $newAttributes = ['active' => !$user->active];
            $userModel->update($user->id, $newAttributes);

            $this->logService->logUpdate(
                auth()->id(),
                UserModel::class,
                $user->id,
                $oldAttributes,
                $newAttributes
            );
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}