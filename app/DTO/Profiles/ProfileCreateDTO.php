<?php

namespace App\DTO\Profiles;

use CodeIgniter\HTTP\Files\UploadedFile;

class ProfileCreateDTO
{
    /**
     * @param string $userName
     * @param string $userRole
     * @param string $userEmail
     * @param string $userPassword
     * @param string|null $firstName
     * @param string|null $lastName
     * @param string|null $description
     * @param UploadedFile|null $profileImage
     */
    public function __construct(
        public string $userName,
        public string $userRole,
        public string $userEmail,
        public string $userPassword,
        public string|null $firstName,
        public string|null $lastName,
        public string|null $description,
        public UploadedFile|null $profileImage
    )
    {
    }
}