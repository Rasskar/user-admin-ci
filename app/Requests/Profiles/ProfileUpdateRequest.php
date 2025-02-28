<?php

namespace App\Requests\Profiles;

use App\Modules\Infrastructure\Request\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    protected function rules(): array
    {
        $userId = $this->request->getPost('userId');

        return [
            'userId' => ['required', 'integer', 'is_not_unique[users.id]'],
            'userName'   => ['required', 'string', 'min_length[3]', 'max_length[50]', "is_unique[users.username,id,{$userId}]"],
            'userRole'   => ['required', 'string'],
            'firstName'  => ['permit_empty', 'string', 'max_length[50]'],
            'lastName'   => ['permit_empty', 'string', 'max_length[50]'],
            'description'=> ['permit_empty', 'string'],
            'profileImage' => ['permit_empty'],
        ];
    }

    protected function messages(): array
    {
        return [
            'userId' => lang('Validation.userId'),
            'userRole' => lang('Validation.userRole'),
            'userName' => lang('Validation.userName'),
            'firstName' => lang('Validation.firstName'),
            'lastName' => lang('Validation.lastName'),
            'description' => lang('Validation.description'),
        ];
    }
}