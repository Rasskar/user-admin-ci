<?php

namespace App\Controllers\Profiles;

use App\Controllers\BaseController;
use App\Factories\ExportProfileDataFactory;
use App\Services\Profiles\ProfileDataService;
use Exception;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class ProfileExportController extends BaseController
{
    public function export(string $type, int $userId)
    {
        try {
            $profileData = (new ProfileDataService($userId))->getData();

            $exporter = ExportProfileDataFactory::create($type);
            return $exporter->export($profileData);
        } catch (Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}