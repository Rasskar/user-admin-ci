<?php

namespace App\Modules\Infrastructure\Contracts;

use CodeIgniter\HTTP\ResponseInterface;

interface ExportInterface
{
    public function export(array $data): ResponseInterface;
}