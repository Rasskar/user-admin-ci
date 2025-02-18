<?php

/**
 * @var bool $isShow
 * @var User $userModel
 * @var Profile $profileModel
 */

use App\Models\Profile;
use App\Models\User;

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

<!-- Блок уведомлений -->
<div id="alertBox" class="alert d-none" role="alert">Ошибка</div>

<form id="profileForm" enctype="multipart/form-data" method="POST">
    <?= csrf_field() ?>
    <div class="row">
        <!-- Левая колонка с текстовыми полями -->
        <div class="col-md-8">
            <div class="mb-3">
                <label for="userRole" class="form-label">Роль</label>
                <select class="form-control" id="userRole" name="userRole" <?= $isShow || !auth()->user()->inGroup('admin') ? 'disabled' : '' ?>>
                    <?php foreach (config('AuthGroups')->groups as $key => $group) : ?>
                        <option value="<?= $key ?>" <?= auth()->user()->inGroup($key) ? 'selected' : '' ?>><?= $group['title'] ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (!$isShow && !auth()->user()->inGroup('admin')) : ?>
                    <input type="hidden" name="userRole" value="<?= auth()->user()->getGroups()[0] ?>">
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="userName" class="form-label">Никнейм</label>
                <input type="text" class="form-control" id="userName" name="userName" value="<?= esc($userModel->username) ?>" required <?= $isShow ? 'readonly' : '' ?>>
            </div>
            <div class="mb-3">
                <label for="firstName" class="form-label">Имя</label>
                <input type="text" class="form-control" id="firstName" name="firstName" value="<?= esc($profileModel->first_name) ?>" <?= $isShow ? 'readonly' : '' ?>>
            </div>
            <div class="mb-3">
                <label for="lastName" class="form-label">Фамилия</label>
                <input type="text" class="form-control" id="lastName" name="lastName" value="<?= esc($profileModel->last_name) ?>" <?= $isShow ? 'readonly' : '' ?>>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Описание</label>
                <textarea class="form-control" id="description" name="description" rows="3" <?= $isShow ? 'readonly' : '' ?>><?= esc($profileModel->description) ?></textarea>
            </div>
        </div>

        <!-- Правая колонка с фото -->
        <div class="col-md-4 text-center">
            <div class="profile-placeholder">
                <img id="photoPreview" src="<?= esc($profileModel->photo_link ?? '/assets/images/default-avatar.png') ?>" alt="Фото профиля" class="profile-img">
            </div>
            <?php if (!$isShow) : ?>
                <div>
                    <input type="file" class="form-control d-none" id="photoLink" name="profileImage" accept="image/*">
                    <button type="button" class="btn btn-secondary mt-2" onclick="document.getElementById('photoLink').click()">Загрузить фотографию</button>
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




