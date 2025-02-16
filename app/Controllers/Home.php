<?php

namespace App\Controllers;

use App\Models\Profile;
use App\Models\User;
use Carbon\Carbon;
use CodeIgniter\Shield\Authentication\Authenticators\Session;
use CodeIgniter\Shield\Entities\Group;
use CodeIgniter\Shield\Models\GroupModel;
use CodeIgniter\Shield\Models\UserIdentityModel;

class Home extends BaseController
{
    public function index(): string
    {



        return view('welcome_message');
    }

    public function sendTest()
    {
        $this->transactionDb = db_connect();
        dd($this->transactionDb);


        $email = service('email');

        $email->setFrom('no-reply@user-admin-ci.com', 'User Admin CI');
        $email->setTo('test@example.com');
        $email->setSubject('Тестовое письмо от CodeIgniter');
        $email->setMessage('<p>Привет! Это тестовое письмо от CodeIgniter 4.</p>');

        if ($email->send()) {
            return '✅ Письмо успешно отправлено!';
        } else {
            return '❌ Ошибка при отправке письма:<br>' . $email->printDebugger(['headers']);
        }
    }
}
