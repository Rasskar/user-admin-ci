<?php

namespace App\DTO\Profiles;

class ProfileQueryDto
{
    /**
     * @param int $draw
     * @param array $columns
     * @param array $order
     * @param int $offset
     * @param int $limit
     * @param int $search
     */
    public function __construct(
        public int $draw,
        public array $columns,
        public array $order,
        public int $offset,
        public int $limit,
        public string|null $search
    )
    {
    }
}