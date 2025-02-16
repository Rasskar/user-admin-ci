<?php

namespace App\Modules\Infrastructure\Traits;

use CodeIgniter\Database\BaseConnection;

trait TransactionTrait
{
    /**
     * @var BaseConnection|null
     */
    private ?BaseConnection $transactionDb = null;

    /**
     * @return void
     */
    protected function beginTransaction(): void
    {
        $transactionDb = $this->getDB();

        if ($transactionDb->transDepth() === 0) {
            $transactionDb->transBegin();
        }
    }

    /**
     * @return void
     */
    protected function commitTransaction(): void
    {
        $transactionDb = $this->getDB();

        if ($transactionDb->transDepth() === 1) {
            $transactionDb->transCommit();
        }
    }

    /**
     * @return void
     */
    protected function rollbackTransaction(): void
    {
        $transactionDb = $this->getDB();

        if ($transactionDb->transDepth() === 1) {
            $transactionDb->transRollback();
        }
    }

    /**
     * @return BaseConnection
     */
    protected function getDB(): BaseConnection
    {
        if ($this->transactionDb === null) {
            $this->transactionDb = db_connect();
        }
        return $this->transactionDb;
    }
}