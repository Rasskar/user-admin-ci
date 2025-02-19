<?php

namespace App\Modules\Infrastructure\DTO;

interface DTOArrayInterface
{
    /**
     * @return array
     */
    public function toArray(): array;
}