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
use CodeIgniter\Shield\Models\GroupModel;
use CodeIgniter\Shield\Models\UserIdentityModel;
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

    public function delete(int $userId)
    {
        $db = db_connect();
        $db->transBegin();

        try {
            $userModel = new UserModel();
            $profileModel = new ProfileModel();
            $identityModel = new UserIdentityModel();
            $groupModel = new GroupModel();

            $user = $userModel->find($userId);
            if (!$user) {
                throw new DataException("Пользователь с ID $userId не найден.");
            }

            // Удаляем связанные записи в `auth_identities`
            $identityModel->where('user_id', $userId)->delete();

            // Удаляем связанные записи в `auth_groups_users`
            $groupModel->where('user_id', $userId)->delete();

            // Удаляем профиль
            $profileModel->where('user_id', $userId)->delete();

            // Удаляем пользователя
            $userModel->delete($userId);

            $db->transCommit();

            return redirect()->to('/profiles')->with('success', 'Пользователь успешно удален.');
        } catch (Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Ошибка удаления: ' . $e->getMessage());
        }
    }
}