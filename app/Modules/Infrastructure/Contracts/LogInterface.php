<?php

namespace App\Modules\Infrastructure\Contracts;

interface LogInterface
{
    /**
     * @param string $action
     * @param int|null $userId
     * @param string|null $model
     * @param int|null $modelId
     * @param array $oldData
     * @param array $newData
     * @return void
     */
    public function log(
        string $action,
        int|null $userId = null,
        string|null $model = null,
        int|null $modelId = null,
        array $oldData = [],
        array $newData = []
    ): void;
}