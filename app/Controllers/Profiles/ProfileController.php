<?php

namespace App\Controllers\Profiles;

use App\Controllers\BaseController;
use App\Entities\ProfileEntity;
use App\Models\ProfileModel;
use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\Shield\Entities\User;

class ProfileController extends BaseController
{
    const ACTION_SHOW = 'show';
    const ACTION_EDIT = 'edit';
    const ACTION_ADD = 'add';

    /**
     * @param int|null $userId
     * @param string $action
     * @return string
     */
    public function show(int|null $userId, string $action = 'show'): string
    {
        $user = (new UserModel())->find($userId);

        if (!$user) {
            throw PageNotFoundException::forPageNotFound("User with ID $userId not found.");
        }

        $profile = (new ProfileModel())->where(['user_id' => $userId])->first();

        return view('profiles/profile', [
            'action' => $action,
            'userModel' => $user,
            'profileModel' => $profile
        ]);
    }

    /**
     * @param int $userId
     * @return string
     */
    public function edit(int $userId): string
    {
        return $this->show($userId, 'edit');
    }

    /**
     * @return string
     */
    public function add(): string
    {
        return view('profiles/profile', [
            'action' => 'add',
            'userModel' => new User(),
            'profileModel' => new ProfileEntity()
        ]);
    }
}