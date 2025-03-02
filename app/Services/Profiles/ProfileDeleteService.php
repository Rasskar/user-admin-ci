<?php

namespace App\Services\Profiles;

use App\Models\ProfileModel;
use App\Models\UserModel;
use App\Modules\Infrastructure\Services\Logs\DatabaseLogService;
use App\Services\Logs\LogModelService;
use App\Models\GroupModel;
use App\Services\WebSockets\WebSocketNotifierService;
use CodeIgniter\Shield\Models\UserIdentityModel;
use Exception;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Throwable;

class ProfileDeleteService
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
        $db = db_connect();
        $db->transBegin();

        try {
            $this->deleteRecord(UserIdentityModel::class, 'user_id', $this->userId);
            $this->deleteRecord(GroupModel::class, 'user_id', $this->userId);
            $this->deleteRecord(ProfileModel::class, 'user_id', $this->userId);
            $this->deleteRecord(UserModel::class, 'id', $this->userId);

            WebSocketNotifierService::sendEvent('refresh', 'profiles');
            WebSocketNotifierService::sendEvent('refresh', 'history');

            $db->transCommit();
        } catch (Exception $exception) {
            $db->transRollback();
            throw $exception;
        }
    }

    /**
     * @param string $modelClass
     * @param string $field
     * @param int $value
     * @return void
     */
    private function deleteRecord(string $modelClass, string $field, int $value): void
    {
        $model = new $modelClass();
        $record = $model->where($field, $value)->first();

        if (!$record) {
            throw new NotFoundResourceException($modelClass . ' not found.');
        }

        $model->delete($record->id);
        $this->logService->logDelete(auth()->id(), $modelClass, $record->id, $record->toArray());
    }
}