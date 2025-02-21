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
            'userId' => [
                'required'      => 'Поле "ID пользователя" обязательно.',
                'integer'       => 'Поле "ID пользователя" должно быть числом.',
                'is_not_unique' => 'Такой пользователь не найден в базе данных.',
            ],
            'userRole' => [
                'string'  => 'Поле "Роль" должно быть строкой.',
            ],
            'userName' => [
                'required'    => 'Поле "Никнейм" обязательно для заполнения.',
                'string'      => 'Поле "Никнейм" должно быть строкой.',
                'min_length'  => 'Поле "Никнейм" должно содержать минимум 3 символа.',
                'max_length'  => 'Поле "Никнейм" не может содержать более 50 символов.',
                'is_unique'   => 'Такой никнейм уже занят. Выберите другой.',
            ],
            'firstName' => [
                'string'      => 'Поле "Имя" должно быть строкой.',
                'max_length'  => 'Поле "Имя" не может содержать более 50 символов.',
            ],
            'lastName' => [
                'string'      => 'Поле "Фамилия" должно быть строкой.',
                'max_length'  => 'Поле "Фамилия" не может содержать более 50 символов.',
            ],
            'description' => [
                'string'      => 'Поле "Описание" должно быть строкой.',
            ],
        ];
    }
}