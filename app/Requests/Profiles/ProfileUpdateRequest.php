<?php

namespace App\Requests\Profiles;

use App\Modules\Infrastructure\Request\FormRequest;
use CodeIgniter\Validation\Exceptions\ValidationException;
use Config\Services;

class ProfileUpdateRequest extends FormRequest
{
    protected function rules(): array
    {
        return [
            'userName'   => ['required', 'string', 'min_length[3]', 'max_length[50]', 'is_unique[users.username]'],
            'userRole'   => ['permit_empty', 'string'],
            'firstName'  => ['permit_empty', 'string', 'max_length[50]'],
            'lastName'   => ['permit_empty', 'string', 'max_length[50]'],
            'description'=> ['permit_empty', 'string'],
            'profileImage' => [
                'permit_empty',
                'is_image[profileImage]',
                'max_size[profileImage,2048]',
                'mime_in[profileImage,image/jpeg,image/png,image/webp,image/gif,image/bmp,image/tiff]'
            ],
        ];
    }

    protected function messages(): array
    {
        return [
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
            'profileImage' => [
                'is_image'    => 'Файл должен быть изображением (JPG, PNG, WEBP, GIF, BMP, TIFF).',
                'max_size'    => 'Размер изображения не должен превышать 2MB.',
                'mime_in'     => 'Изображение должно быть формата JPG, PNG, WEBP, GIF, BMP или TIFF.',
            ],
        ];
    }
}