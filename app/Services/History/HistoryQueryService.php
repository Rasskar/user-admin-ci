<?php

namespace App\Services\History;

use App\DTO\History\HistoryQueryDto;
use App\Models\UserLogModel;

class HistoryQueryService
{
    /**
     * @var array
     */
    protected array $data = [];

    /**
     * @var int
     */
    protected int $totalRecords = 0;

    /**
     * @var int
     */
    protected int $filteredRecords = 0;

    /**
     * @param HistoryQueryDto $dto
     */
    public function __construct(
        protected HistoryQueryDto $dto
    )
    {
        $this->query();
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return int
     */
    public function getTotalRecords(): int
    {
        return $this->totalRecords;
    }

    /**
     * @return int
     */
    public function getFilteredRecords(): int
    {
        return $this->filteredRecords;
    }

    /**
     * @return void
     */
    protected function query(): void
    {
        $userLogsModel = new UserLogModel();

        $query = $userLogsModel
            ->select('users_logs.*, users.username')
            ->join('users', 'users.id = users_logs.user_id', 'left');

        $this->totalRecords = $query->countAllResults(false);

        if (!empty($this->dto->search)) {
            $query->like('users.username', $this->dto->search);
        }

        $this->filteredRecords = $query->countAllResults(false);

        $this->data = $query
            ->orderBy('users_logs.created_at', 'DESC')
            ->limit($this->dto->limit, $this->dto->offset)
            ->get()
            ->getResultArray();
    }
}