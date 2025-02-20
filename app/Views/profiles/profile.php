<?php

/**
 * @var bool $isShow
 * @var User $userModel
 * @var ProfileEntity $profileModel
 */

use App\Entities\ProfileEntity;
use CodeIgniter\Shield\Entities\User;

?>

<?= $this->extend('layouts/app_layout') ?>

<?= $this->section('title') ?>
UACi | Профиль пользователя
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<link href="<?= base_url('assets/css/profiles/profile.css?v=' . time()) ?>" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script src="<?= base_url('assets/js/profiles/profile.js?v=' . time()) ?>"></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<h2 class="mb-4">Профиль</h2>

<?php if (session()->has('errors')): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach (session('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if (session()->has('success')): ?>
    <div class="alert alert-success">
        <?= esc(session('success')) ?>
    </div>
<?php endif; ?>

<form id="profileForm" action="<?= base_url('/profile/update') ?>" enctype="multipart/form-data" method="POST">
    <?= csrf_field() ?>
    <input type="hidden" id="userId" name="userId" value="<?= $userModel->id ?>">

    <div class="row">
        <div class="col-md-8">
            <div class="mb-3">
                <label for="userRole" class="form-label">Роль</label>
                <select class="form-control <?= session('errors.userRole') ? 'is-invalid' : '' ?>" id="userRole" name="userRole" <?= $isShow || !auth()->user()->inGroup('admin') ? 'disabled' : '' ?>>
                    <?php foreach (config('AuthGroups')->groups as $key => $group) : ?>
                        <option value="<?= $key ?>" <?= old('userRole', auth()->user()->getGroups()[0]) == $key ? 'selected' : '' ?>>
                            <?= $group['title'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (!$isShow && !auth()->user()->inGroup('admin')) : ?>
                    <input type="hidden" name="userRole" value="<?= old('userRole', auth()->user()->getGroups()[0]) ?>">
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="userName" class="form-label">Никнейм</label>
                <input type="text" class="form-control <?= session('errors.userName') ? 'is-invalid' : '' ?>" id="userName" name="userName" value="<?= old('userName', esc($userModel->username)) ?>" <?= $isShow ? 'disabled' : '' ?>>
            </div>
            <div class="mb-3">
                <label for="firstName" class="form-label">Имя</label>
                <input type="text" class="form-control <?= session('errors.firstName') ? 'is-invalid' : '' ?>" id="firstName" name="firstName" value="<?= old('firstName', esc($profileModel->first_name)) ?>" <?= $isShow ? 'disabled' : '' ?>>
            </div>
            <div class="mb-3">
                <label for="lastName" class="form-label">Фамилия</label>
                <input type="text" class="form-control <?= session('errors.lastName') ? 'is-invalid' : '' ?>" id="lastName" name="lastName" value="<?= old('lastName', esc($profileModel->last_name)) ?>" <?= $isShow ? 'disabled' : '' ?>>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Описание</label>
                <textarea class="form-control <?= session('errors.description') ? 'is-invalid' : '' ?>" id="description" name="description" rows="6" <?= $isShow ? 'disabled' : '' ?>><?= old('description', esc($profileModel->description)) ?></textarea>
            </div>
        </div>

        <div class="col-md-4 text-center">
            <div class="profile-placeholder">
                <img id="photoPreview" src="<?= esc($profileModel->getPhoto()) ?>" alt="Фото профиля" class="profile-img">
            </div>
            <?php if (!$isShow) : ?>
                <div>
                    <input type="file" class="form-control d-none" id="photoLink" name="profileImage" accept="image/*">
                    <button type="button" class="btn btn-secondary mt-2" onclick="document.getElementById('photoLink').click()">Загрузить фотографию</button>
                </div>
            <?php else: ?>
                <div class="button-container">
                    <a class="btn btn-primary edit" href="<?= base_url('/profile/edit/' . $userModel->id) ?>" role="button">Редактировать</a>
                    <a class="btn btn-danger delete" href="" role="button">Удалить</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!$isShow) : ?>
        <button type="submit" class="btn btn-primary mt-3 load">Сохранить</button>
    <?php endif; ?>
</form>

<!-- Модальное окно для обрезки фото -->
<?php if (!$isShow) : ?>
    <div class="modal fade" id="cropperModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Обрезка изображения</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <canvas id="cropCanvas"></canvas>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-primary" id="cropSave">Сохранить</button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>




