<?php

namespace App\Requests\Profiles;

use App\Modules\Infrastructure\Request\FormRequest;

class ProfileCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'userEmail' => ['required', 'valid_email', 'is_unique[auth_identities.secret]'],
            'userPassword' => ['required', 'min_length[8]', 'max_length[50]', 'regex_match[/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}$/]'],
            'userName'   => ['required', 'string', 'min_length[3]', 'max_length[50]', "is_unique[users.username]"],
            'userRole'   => ['required', 'string'],
            'firstName'  => ['permit_empty', 'string', 'max_length[50]'],
            'lastName'   => ['permit_empty', 'string', 'max_length[50]'],
            'description'=> ['permit_empty', 'string'],
            'profileImage' => ['permit_empty'],
        ];
    }

    public function messages(): array
    {
        return [
            'userEmail' => lang('Validation.userEmail'),
            'userPassword' => lang('Validation.userPassword'),
            'userRole' => lang('Validation.userRole'),
            'userName' => lang('Validation.userName'),
            'firstName' => lang('Validation.firstName'),
            'lastName' => lang('Validation.lastName'),
            'description' => lang('Validation.description'),
        ];
    }
}