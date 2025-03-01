<?php

namespace App\Services\Logs;

use App\Modules\Infrastructure\Contracts\LogInterface;

class LogModelService
{
    /**
     * @param LogInterface $logger
     */
    public function __construct(
        protected LogInterface $logger
    )
    {
    }

    /**
     * @param int $userId
     * @param string $model
     * @param int $modelId
     * @param $newData
     * @return void
     */
    public function logCreate(int $userId, string $model, int $modelId, $newData):void
    {
        $this->logger->log('create', $userId, $model, $modelId, [], $newData);
    }

    /**
     * @param int $userId
     * @param string $model
     * @param int $modelId
     * @param array $oldData
     * @param array $newData
     * @return void
     */
    public function logUpdate(int $userId, string $model, int $modelId, array $oldData, array $newData):void
    {
        $this->logger->log('update', $userId, $model, $modelId, $oldData, $newData);
    }

    /**
     * @param int $userId
     * @param string $model
     * @param int $modelId
     * @param array $oldData
     * @return void
     */
    public function logDelete(int $userId, string $model, int $modelId, array $oldData): void
    {
        $this->logger->log('delete', $userId, $model, $modelId, $oldData);
    }
}