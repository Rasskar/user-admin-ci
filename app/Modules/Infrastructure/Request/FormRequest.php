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
     * @var array
     */
    protected array $errors = [];

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
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return empty($this->errors);
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
            $this->errors = $validation->getErrors();
            return;
        }

        $this->data = $validation->getValidated();
    }
}
