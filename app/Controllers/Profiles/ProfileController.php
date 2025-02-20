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
        $formRequest = new ProfileUpdateRequest($this->request);

        if (!$formRequest->isValid()) {
            return redirect()->back()->withInput()->with('errors', $formRequest->getErrors());
        }

        try {
            $dto = new ProfileUpdateDto(...$formRequest->getData());
            (new ProfileUpdateService($dto))->execute();

            return redirect()->to('/profile/edit/' . $dto->userId)->with('success', 'Профиль обновлен!');
        } catch (NotFoundResourceException|DataException $exception) {
            return redirect()->back()->withInput()->with('error', $exception->getMessage());
        } catch (Exception $exception) {
            return redirect()->back()->withInput()->with('error', 'Произошла ошибка. Попробуйте снова.');
        }
    }
}