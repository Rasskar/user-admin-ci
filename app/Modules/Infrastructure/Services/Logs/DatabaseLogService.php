<?php

namespace App\Modules\Infrastructure\Services\Logs;

use App\Models\UserLogModel;
use App\Modules\Infrastructure\Contracts\LogInterface;
use Exception;

class DatabaseLogService implements LogInterface
{
    private const ALLOWED_ACTIONS = ['login', 'update', 'create', 'delete'];

    /**
     * @param string $action
     * @param int|null $userId
     * @param string|null $model
     * @param int|null $modelId
     * @param array $oldData
     * @param array $newData
     * @return void
     * @throws \ReflectionException
     */
    public function log(
        string $action,
        int|null $userId = null,
        string|null $model = null,
        int|null $modelId = null,
        array $oldData = [],
        array $newData = []
    ): void
    {
        if (!in_array($action, self::ALLOWED_ACTIONS)) {
            throw new Exception("Invalid action type: $action");
        }

        $changedFields = [];
        $newValues = [];

        if (!empty($oldData) && !empty($newData)) {
            foreach ($oldData as $key => $oldValue) {
                if (isset($newData[$key]) && $newData[$key] !== $oldValue) {
                    $changedFields[$key] = $oldValue;
                    $newValues[$key] = $newData[$key];
                }
            }
        } elseif (!empty($newData)) {
            $newValues = $newData;
        } elseif (!empty($oldData)) {
            $changedFields = $oldData;
        }

        $request = service('request');

        $logData = [
            'user_id' => $userId,
            'action' => $action,
            'model' => $model,
            'model_id' => $modelId,
            'old_data' => !empty($changedFields) ? json_encode($changedFields) : null,
            'new_data' => !empty($newValues) ? json_encode($newValues) : null,
            'ip' => $request->getIPAddress(),
            'user_agent' => $request->getUserAgent(),
        ];

        (new UserLogModel())->insert($logData);
    }
}