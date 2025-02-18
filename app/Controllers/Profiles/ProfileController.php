<?php

namespace App\Controllers\Profiles;

use App\Controllers\BaseController;
use App\Models\Profile;
use App\Models\User;
use App\Requests\Profiles\ProfileUpdateRequest;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Validation\Exceptions\ValidationException;

class ProfileController extends BaseController
{
    /**
     * @param int $userId
     * @param string $action
     * @return string
     */
    public function show(int $userId, string $action = 'show'): string
    {
        $user = (new User())->find($userId);

        if (!$user) {
            throw PageNotFoundException::forPageNotFound("Пользователь с ID $userId не найден.");
        }

        $profile = (new Profile())->where(['user_id' => $userId])->first();

        //dd(auth()->user()->getGroups());

        return view('profiles/profile', [
            'isShow' => ($action == 'show'),
            'userModel' => $user,
            'profileModel' => $profile
        ]);
    }

    /**
     * @param int $userId
     * @return RedirectResponse|string
     */
    public function edit(int $userId): RedirectResponse|string
    {
        if (auth()->id() != $userId && !auth()->user()->inGroup('admin')) {
            return redirect()->to('/profile/' . $userId);
        }

        return $this->show($userId, 'edit');
    }

    public function update()
    {
        try {
            $requestData = array_merge($this->request->getPost(), $this->request->getFiles());

            (new ProfileUpdateRequest($requestData))->validate();


            return $this->response->setStatusCode(200)->setJSON([
                'message' => 'Профиль успешно обновлен!',
                'csrf_token' => csrf_hash()
            ]);
        } catch (ValidationException $exception) {
            return $this->response->setStatusCode(422)->setJSON([
                    'message' => $exception->getMessage(),
                    'errors' => $exception->getMessage(),
                    'csrf_token' => csrf_hash()
            ]);
        } catch (\Exception $exception) {
            return $this->response->setStatusCode(500)->setJSON([
                    'message' => 'Произошла внутренняя ошибка сервера.',
                    'error' => $exception->getMessage()
            ]);
        }
        echo "<pre>";
        print_r(array_merge($this->request->getPost(), $this->request->getFiles()));
        echo "</pre>";

        echo "<pre>";
        print_r($this->request);
        echo "</pre>";
    }
}