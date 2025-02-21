<?php

namespace App\Services\Files;

use CodeIgniter\HTTP\Files\UploadedFile;

class FileSaveService
{
    /**
     * @param UploadedFile $file
     * @param string $path
     * @param int $userId
     */
    public function __construct(
        protected UploadedFile $file,
        protected string $path,
        protected int $userId
    )
    {
    }

    /**
     * @return string|null
     */
    public function save(): string|null
    {
        if (!$this->file->isValid() || $this->file->hasMoved()) {
            return null;
        }

        $uploadPath = WRITEPATH . 'uploads/' . $this->path;

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $newFileName = $this->userId . '_' . time() . '.' . $this->file->getExtension();
        $this->file->move($uploadPath, $newFileName);

        return $this->path . '/' . $newFileName;
    }
}