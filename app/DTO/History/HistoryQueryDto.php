<?php

namespace App\DTO\History;

class HistoryQueryDto
{
    /**
     * @param int $draw
     * @param array $columns
     * @param int $offset
     * @param int $limit
     * @param string|null $search
     */
    public function __construct(
        public int $draw,
        public array $columns,
        public int $offset,
        public int $limit,
        public string|null $search
    )
    {
    }
}