<?php

namespace App\Models;

use CodeIgniter\Model;

class Profile extends Model
{
    /**
     * @var string
     */
    protected $table = 'profiles';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var bool
     */
    protected $useAutoIncrement = true;

    /**
     * @var string[]
     */
    protected $allowedFields = [
        'first_name',
        'last_name',
        'description',
        'photo_link',
        'telegram_nickname',
        'created_at',
        'updated_at'
    ];

    /**
     * @var bool
     */
    protected $useTimestamps = true;

    /**
     * @var string
     */
    protected $dateFormat = 'datetime';

    /**
     * @var string
     */
    protected $createdField = 'created_at';

    /**
     * @var string
     */
    protected $updatedField = 'updated_at';
}
