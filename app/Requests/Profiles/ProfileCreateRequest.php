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
            'userEmail' => [
                'required' => 'Поле "Email" обязательно.',
                'valid_email' => 'Введите корректный "Email".',
                'is_unique' => 'Такой "Eamil" уже зарегистрирован.'
            ],
            'userPassword' => [
                'required' => 'Поле "Пароль" обязательно.',
                'min_length' => '"Пароль" должен быть не менее 8 символов.',
                'max_length' => '"Пароль" не может превышать 50 символов.',
                'regex_match' => '"Пароль" должен содержать хотя бы одну заглавную букву, одну строчную букву, одну цифру и один спецсимвол.'
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