<?php

namespace App\Models;

use App\Entities\UserLogEntity;
use Carbon\Carbon;
use CodeIgniter\Model;

class UserLogModel extends Model
{
    /**
     * @var string
     */
    protected $table = 'users_logs';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var string
     */
    protected $returnType = UserLogEntity::class;

    /**
     * @var bool
     */
    protected $useTimestamps = false;

    /**
     * @var string
     */
    protected $createdField = 'created_at';

    /**
     * @var string[]
     */
    protected $allowedFields = [
        'user_id',
        'action',
        'model',
        'model_id',
        'old_data',
        'new_data',
        'ip',
        'user_agent',
    ];

    /**
     * @var string[]
     */
    protected $beforeInsert = ['setCreatedAt'];

    /**
     * @param array $data
     * @return array
     */
    protected function setCreatedAt(array $data): array
    {
        if (!isset($data['data']['created_at'])) {
            $data['data']['created_at'] = Carbon::now();
        }

        return $data;
    }
}