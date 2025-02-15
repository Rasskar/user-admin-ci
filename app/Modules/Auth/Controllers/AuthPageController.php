<?php

namespace App\Modules\Auth\Controllers;

use App\Controllers\BaseController;

class AuthPageController extends BaseController
{
    /**
     * @return string
     */
    public function index(): string
    {
        return view('auth/index');
    }
}