<?php

namespace App\Services\Profiles;

use App\DTO\Profiles\ProfileQueryDto;
use App\Models\UserModel;

class ProfileQueryService
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
     * @param ProfileQueryDto $dto
     */
    public function __construct(
        protected ProfileQueryDto $dto
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
        $userModel = new UserModel();

        $builder = $userModel->select(
            'users.id AS id, users.username AS username, auth_identities.secret AS email,
             CASE 
                  WHEN users.last_active >= DATE_SUB(NOW(), INTERVAL 5 MINUTE) 
                  THEN "online" 
                  ELSE "offline" 
             END AS status'
        )
            ->join('auth_identities', 'auth_identities.user_id = users.id')
            ->where('users.deleted_at', null);

        $this->totalRecords = $builder->countAllResults(false);

        if (!empty($this->dto->search)) {
            $builder->groupStart()
                ->like('users.username', $this->dto->search)
                ->orLike('auth_identities.secret', $this->dto->search)
                ->groupEnd();
        }

        $this->filteredRecords = $builder->countAllResults(false);

        if (!empty($this->dto->order)) {
            foreach ($this->dto->order as $order) {
                $builder->orderBy($this->dto->columns[$order['column']]['data'], $order['dir']);
            }
        } else {
            $builder->orderBy('id', 'DESC');
        }

        $builder->limit($this->dto->limit, $this->dto->offset);

        $this->data = $builder->get()->getResultArray();
    }
}