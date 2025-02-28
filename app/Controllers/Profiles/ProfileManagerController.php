<?php

namespace App\Controllers\Profiles;

use App\Controllers\BaseController;
use App\DTO\Profiles\ProfileCreateDTO;
use App\DTO\Profiles\ProfileUpdateDTO;
use App\Requests\Profiles\ProfileCreateRequest;
use App\Requests\Profiles\ProfileUpdateRequest;
use App\Services\Profiles\ProfileCreateService;
use App\Services\Profiles\ProfileDeleteService;
use App\Services\Profiles\ProfileToggleStatusService;
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
                "Profile successfully created! Login: {$dto->userEmail}, Password: {$dto->userPassword}"
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

            return redirect()->to('/profile/edit/' . $dto->userId)->with('success', 'Profile updated!');
        } catch (NotFoundResourceException|DataException $exception) {
            return redirect()->back()->withInput()->with('error', $exception->getMessage());
        } catch (Exception $exception) {
            return redirect()->back()->withInput()->with('error', 'An error has occurred. Try again.');
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

            return redirect()->to('/profiles')->with('success', 'User deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Delete error: ' . $e->getMessage());
        }
    }

    /**
     * @param $userId
     * @return RedirectResponse
     */
    public function toggleStatus($userId): RedirectResponse
    {
        try {
            (new ProfileToggleStatusService($userId))->execute();

            return redirect()->back()->with('success', 'User status changed successfully.');
        } catch (Exception $e) {
            session()->setFlashdata('error', );
            return redirect()->back()->with('error', 'Error changing user status: ' . $e->getMessage());
        }
    }
}