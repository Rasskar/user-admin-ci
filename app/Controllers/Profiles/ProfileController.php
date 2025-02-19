<?php

namespace App\Controllers\Profiles;

use App\Controllers\BaseController;
use App\DTO\Profiles\ProfileUpdateDto;
use App\Models\ProfileModel;
use App\Models\UserModel;
use App\Requests\Profiles\ProfileUpdateRequest;
use App\Services\Profiles\ProfileUpdateService;
use CodeIgniter\Database\Exceptions\DataException;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;
use Exception;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class ProfileController extends BaseController
{
    /**
     * @param int $userId
     * @param string $action
     * @return string
     */
    public function show(int $userId, string $action = 'show'): string
    {
        $user = (new UserModel())->find($userId);

        if (!$user) {
            throw PageNotFoundException::forPageNotFound("Пользователь с ID $userId не найден.");
        }

        $profile = (new ProfileModel())->where(['user_id' => $userId])->first();

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
        $requestData = (new ProfileUpdateRequest($this->request))->getValidatedData();

        try {
            $dto = new ProfileUpdateDto(...$requestData);
            (new ProfileUpdateService($dto))->updateProfile(auth()->id());

            return $this->response->setStatusCode(200)->setJSON([
                'message' => 'Профиль обновлен',
                'csrf_token' => csrf_hash()
            ]);
        } catch (NotFoundResourceException $exception) {
            return $this->response->setStatusCode(404)->setJSON([
                'message' => $exception->getMessage(),
                'csrf_token' => csrf_hash()
            ]);
        } catch (DataException $exception) {
            return $this->response->setStatusCode(500)->setJSON([
                'message' => $exception->getMessage(),
                'csrf_token' => csrf_hash()
            ]);
        } catch (Exception $exception) {
            return $this->response->setStatusCode(500)->setJSON([
                'message' => $exception->getMessage()//'Ошибка сервера. Перезагрузите страницу и попробуйте снова.'
            ]);
        }
    }
}