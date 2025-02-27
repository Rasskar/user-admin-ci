<?php

namespace App\Controllers\Profiles;

use App\Controllers\BaseController;
use App\DTO\Profiles\ProfileQueryDto;
use App\Models\UserModel;
use App\Services\Profiles\ProfileQueryService;
use App\Services\Profiles\ProfileQueryServiceTwo;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

class ProfilesController extends BaseController
{
    /**
     * @return string
     */
    public function index(): string
    {
        return view('profiles/list');
    }

    /**
     * @return ResponseInterface
     */
    public function datatable(): ResponseInterface
    {
        try {
            throw new Exception("Тестовая ошибка");

            $requestData = $this->request->getGet();

            $dto = new ProfileQueryDto(
                $requestData['draw'],
                $requestData['columns'],
                $requestData['order'],
                $requestData['start'],
                $requestData['length'],
                $requestData['search']['value']
            );

            $service = new ProfileQueryService($dto);

            return $this->response->setStatusCode(200)->setJSON([
                "draw" => (int) $this->request->getGet('draw'),
                "recordsTotal" => $service->getTotalRecords(),
                "recordsFiltered" => $service->getFilteredRecords(),
                "data" => $service->getData()
            ]);
        } catch (Exception $exception) {
            return $this->response->setStatusCode(500)->setJSON(["message" => $exception->getMessage()]);
        }
    }
}