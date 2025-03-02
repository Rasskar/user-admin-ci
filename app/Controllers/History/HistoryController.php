<?php

namespace App\Controllers\History;

use App\Controllers\BaseController;
use App\DTO\History\HistoryQueryDto;
use App\Services\History\HistoryQueryService;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

class HistoryController extends BaseController
{
    /**
     * @return string
     */
    public function index(): string
    {
        return view('history/index');
    }

    /**
     * @return ResponseInterface
     */
    public function datatable(): ResponseInterface
    {
        try {
            $requestData = $this->request->getGet();

            $dto = new HistoryQueryDto(
                $requestData['draw'],
                $requestData['columns'],
                $requestData['start'],
                $requestData['length'],
                $requestData['search']['value']
            );

            $service = new HistoryQueryService($dto);

            return $this->response->setStatusCode(200)->setJSON([
                "draw" => (int)$this->request->getGet('draw'),
                "recordsTotal" => $service->getTotalRecords(),
                "recordsFiltered" => $service->getFilteredRecords(),
                "data" => $service->getData()
            ]);
        } catch (Exception $exception) {
            return $this->response->setStatusCode(500)->setJSON(["message" => $exception->getMessage()]);
        }
    }
}