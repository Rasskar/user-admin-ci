<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class ProfileEntity extends Entity
{
    /**
     * @var array
     */
    protected $attributes = [
        'id'          => null,
        'user_id'     => null,
        'first_name'  => null,
        'last_name'   => null,
        'description' => null,
        'photo_link'  => null,
        'created_at'  => null,
        'updated_at'  => null,
    ];

    /**
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * @return string
     */
    public function getPhoto(): string
    {
        if (!empty($this->attributes['photo_link'])) {
            return base_url('file/' . $this->attributes['photo_link']);
        }

        return base_url('/assets/images/default-avatar.png');
    }
}