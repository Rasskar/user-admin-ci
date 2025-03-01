<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class UserLogEntity extends Entity
{
    /**
     * @var array
     */
    protected $datamap = [];

    /**
     * @var string[]
     */
    protected $dates = ['created_at'];

    /**
     * @var string[]
     */
    protected $casts = [
        'old_data' => 'json',
        'new_data' => 'json',
    ];
}