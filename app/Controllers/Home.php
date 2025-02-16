<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }

    public function sendTestEmail()
    {
        $email = service('email');

        $email->setFrom('no-reply@user-admin-ci.com', 'User Admin CI');
        $email->setTo('test@example.com');
        $email->setSubject('Тестовое письмо от CodeIgniter');
        $email->setMessage('<p>Привет, это тестовое письмо от CodeIgniter 4!</p>');

        if ($email->send()) {
            return "✅ Письмо успешно отправлено!";
        } else {
            return "❌ Ошибка при отправке письма: <br><pre>" . $email->printDebugger(['headers']) . "</pre>";
        }
    }
}
