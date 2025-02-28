<?php

namespace App\Controllers\Files;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class FileController extends BaseController
{
    /**
     * @param string $folder
     * @param string $fileName
     * @return ResponseInterface
     */
    public function getFile(string $folder, string $fileName): ResponseInterface
    {
        $fullPath = WRITEPATH . 'uploads/' . $folder . '/' . $fileName;

        if (!is_file($fullPath)) {
            return $this->response->setStatusCode(404)->setBody('Fail not found');
        }

        return $this->response
            ->setContentType(mime_content_type($fullPath))
            ->setBody(file_get_contents($fullPath));
    }
}