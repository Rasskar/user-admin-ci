<?php

namespace App\Controllers\Profiles;

use App\Controllers\BaseController;

class ProfilesController extends BaseController
{
    public function index()
    {
        return view('profiles/list');
    }
}