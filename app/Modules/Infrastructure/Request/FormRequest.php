<?php

namespace App\Modules\Infrastructure\Request;

use CodeIgniter\Validation\Exceptions\ValidationException;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

abstract class FormRequest
{
    /**
     * @var array
     */
    protected array $data = [];

    /**
     * @param RequestInterface $request
     */
    public function __construct(
        protected RequestInterface $request
    )
    {
        $this->validate();
    }

    /**
     * @return array
     */
    public function getValidatedData(): array
    {
        return $this->data;
    }

    /**
     * @return array
     */
    abstract protected function rules(): array;

    /**
     * @return array
     */
    abstract protected function messages(): array;

    /**
     * @return void
     */
    protected function validate(): void
    {
        $validation = Services::validation();
        $validation->setRules($this->rules(), $this->messages());
        $requestData = array_merge($this->request->getPost(), $this->request->getGet(), $this->request->getFiles());

        if (!$validation->run($requestData)) {
            $response = Services::response()->setStatusCode(422)->setJSON([
                'message' => 'Ошибка валидации',
                'errors' => $validation->getErrors(),
                'csrf_token' => csrf_hash()
            ]);

            $response->send();
            exit;
        }

        $this->data = $validation->getValidated();
    }
}
