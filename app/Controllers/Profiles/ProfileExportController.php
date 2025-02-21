<?php

namespace App\Controllers\Profiles;

use App\Controllers\BaseController;
use App\Factories\ExportProfileDataFactory;
use App\Services\Profiles\ProfileDataService;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

class ProfileExportController extends BaseController
{
    /**
     * @param string $type
     * @param int $userId
     * @return RedirectResponse|ResponseInterface
     */
    public function export(string $type, int $userId): RedirectResponse|ResponseInterface
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