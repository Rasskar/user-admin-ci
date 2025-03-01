<?php

namespace App\Services\Logs;

use App\Modules\Infrastructure\Contracts\LogInterface;

class LogAuthService
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
     * @return void
     */
    public function logAuth(int $userId): void
    {
        $this->logger->log('login', $userId);
    }
}