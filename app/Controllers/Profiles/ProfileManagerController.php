<?php

namespace App\Controllers\Profiles;

use App\Controllers\BaseController;
use App\DTO\Profiles\ProfileCreateDTO;
use App\DTO\Profiles\ProfileUpdateDTO;
use App\Requests\Profiles\ProfileCreateRequest;
use App\Requests\Profiles\ProfileUpdateRequest;
use App\Services\Profiles\ProfileCreateService;
use App\Services\Profiles\ProfileDeleteService;
use App\Services\Profiles\ProfileUpdateService;
use CodeIgniter\Database\Exceptions\DataException;
use CodeIgniter\HTTP\RedirectResponse;
use Exception;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Throwable;

class ProfileManagerController extends BaseController
{
    public function create()
    {
        $formRequest = new ProfileCreateRequest($this->request);

        if (!$formRequest->isValid()) {
            return redirect()->back()->withInput()->with('errors', $formRequest->getErrors());
        }

        try {
            $dto = new ProfileCreateDTO(...$formRequest->getData());
            $service = new ProfileCreateService($dto);
            $service->execute();

            return redirect()->to('/profile/' . $service->getUserId())->with(
                'success',
                "Профиль успешно создан! Логин: {$dto->userEmail}, Пароль: {$dto->userPassword}"
            );
        } catch (Exception $exception) {
            return redirect()->back()->withInput()->with('error', $exception->getMessage());
        }
    }

    /**
     * @return RedirectResponse
     * @throws Throwable
     */
    public function update(): RedirectResponse
    {
        $formRequest = new ProfileUpdateRequest($this->request);

        if (!$formRequest->isValid()) {
            return redirect()->back()->withInput()->with('errors', $formRequest->getErrors());
        }

        try {
            $dto = new ProfileUpdateDTO(...$formRequest->getData());
            (new ProfileUpdateService($dto))->execute();

            return redirect()->to('/profile/edit/' . $dto->userId)->with('success', 'Профиль обновлен!');
        } catch (NotFoundResourceException|DataException $exception) {
            return redirect()->back()->withInput()->with('error', $exception->getMessage());
        } catch (Exception $exception) {
            return redirect()->back()->withInput()->with('error', 'Произошла ошибка. Попробуйте снова.');
        }
    }

    /**
     * @param int $userId
     * @return RedirectResponse
     * @throws Throwable
     */
    public function delete(int $userId): RedirectResponse
    {
        try {
            (new ProfileDeleteService($userId))->execute();

            return redirect()->to('/profiles')->with('success', 'Пользователь успешно удален.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Ошибка удаления: ' . $e->getMessage());
        }
    }
}