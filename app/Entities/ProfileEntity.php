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
    public function getPhotoLink(): string
    {
        dd($this->photo_link);

        /*if (!empty($this->photo_link)) {
            return base_url('file/' . $this->photo_link);
        }*/

        return base_url('/assets/images/default-avatar.png');
    }
}