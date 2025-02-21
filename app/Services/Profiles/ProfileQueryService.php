<?php

namespace App\Services\Profiles;

use App\Models\UserModel;
use CodeIgniter\Pager\Pager;
use CodeIgniter\Shield\Entities\User;

class ProfileQueryService
{
    /**
     * @var User[]
     */
    protected array $users = [];

    /**
     * @var Pager|null
     */
    protected  Pager|null $pager = null;

    /**
     * @param string|null $search
     * @param int $paginate
     */
    public function __construct(
        protected string|null $search,
        protected int $paginate = 15
    )
    {
        $this->query();
    }

    /**
     * @return User[]
     */
    public function getUsers(): array
    {
        return $this->users;
    }

    /**
     * @return Pager|null
     */
    public function getPager(): Pager|null
    {
        return $this->pager;
    }

    public function query(): void
    {
        $userModel = new UserModel();

        $query = $userModel
            ->select('users.id, users.username, auth_identities.secret AS email,
                     CASE 
                          WHEN users.last_active >= DATE_SUB(NOW(), INTERVAL 5 MINUTE) 
                          THEN "online" 
                          ELSE "deactivated" 
                      END AS status')
            ->join('auth_identities', 'auth_identities.user_id = users.id')
            ->where(['users.deleted_at' => null])
            ->orderBy('users.id', 'DESC');

        if (!empty($this->search)) {
            $query = $query
                ->like('users.username', $this->search)
                ->orLike('auth_identities.secret', $this->search);
        }

        $this->users = $query->paginate($this->paginate);
        $this->pager = $userModel->pager;
    }
}