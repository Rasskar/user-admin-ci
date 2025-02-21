<?php

namespace App\Factories;

use App\Modules\Infrastructure\Contracts\ExportInterface;
use App\Services\Exports\ExcelExportProfileDataService;
use App\Services\Exports\WordExportProfileDataService;
use CodeIgniter\Exceptions\PageNotFoundException;

class ExportProfileDataFactory
{
    public static function create(string $type): ExportInterface
    {
        return match (strtolower($type)) {
            'excel' => new ExcelExportProfileDataService(),
            'word'  => new WordExportProfileDataService(),
            default => throw PageNotFoundException::forPageNotFound('Формат экспорта не поддерживается'),
        };
    }
}