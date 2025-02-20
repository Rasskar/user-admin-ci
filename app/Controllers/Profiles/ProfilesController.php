<?php

namespace App\Controllers\Profiles;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Services\Profiles\ProfileQueryService;

class ProfilesController extends BaseController
{
    /**
     * @return string
     */
    public function index(): string
    {
        $service = (new ProfileQueryService($this->request->getGet('search')));

        return view('profiles/list', [
            'users' => $service->getUsers(),
            'pager' => $service->getPager(),
            'search' => $this->request->getGet('search')
        ]);
    }
}