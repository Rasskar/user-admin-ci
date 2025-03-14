<?php

/**
 * @var string $action
 * @var User $userModel
 * @var ProfileEntity $profileModel
 */

use App\Controllers\Profiles\ProfileController;
use App\Entities\ProfileEntity;
use CodeIgniter\Shield\Entities\User;

?>

<?= $this->extend('layouts/app_layout') ?>

<?= $this->section('title') ?>
UACi | <?= lang('Titles.profile') ?>
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
<h2 class="mb-4"><?= lang('Titles.profile') ?></h2>

<div class="alert-container">
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
</div>

<form id="profileForm" data-action="<?= $action ?>" action="<?= $action == ProfileController::ACTION_ADD ? base_url('/profile/create') : base_url('/profile/update') ?>" enctype="multipart/form-data" method="POST">
    <?= csrf_field() ?>
    <?php if ($action == ProfileController::ACTION_EDIT) : ?>
        <input type="hidden" id="userId" name="userId" value="<?= $userModel->id ?>">
    <?php endif; ?>
    <div class="row">
        <div class="col-md-8">
            <div class="mb-3">
                <label for="userRole" class="form-label"><?= lang('Forms.profile.role') ?></label>
                <select class="form-control <?= session('errors.userRole') ? 'is-invalid' : '' ?>" id="userRole" name="userRole" <?= $action == ProfileController::ACTION_SHOW || !auth()->user()->inGroup('admin') ? 'disabled' : '' ?>>
                    <?php foreach (config('AuthGroups')->groups as $key => $group) : ?>
                        <option value="<?= $key ?>" <?= old('userRole', $userModel->getGroups()[0] ?? '') == $key ? 'selected' : '' ?>>
                            <?= $group['title'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if ($action == ProfileController::ACTION_EDIT && !auth()->user()->inGroup('admin')) : ?>
                    <input type="hidden" name="userRole" value="<?= old('userRole', auth()->user()->getGroups()[0]) ?>">
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="userName" class="form-label"><?= lang('Forms.profile.nickname') ?></label>
                <input type="text" class="form-control <?= session('errors.userName') ? 'is-invalid' : '' ?>" id="userName" name="userName" value="<?= old('userName', esc($userModel->username)) ?>" <?= $action == ProfileController::ACTION_SHOW ? 'disabled' : '' ?>>
            </div>
            <?php if ($action == ProfileController::ACTION_ADD) : ?>
                <div class="mb-3">
                    <label for="userEmail" class="form-label"><?= lang('Forms.profile.email') ?></label>
                    <input type="email" class="form-control <?= session('errors.userEmail') ? 'is-invalid' : '' ?>" id="userEmail" name="userEmail" value="<?= old('userEmail', '') ?>">
                </div>
                <div class="mb-3">
                    <label for="userPassword" class="form-label"><?= lang('Forms.profile.password') ?></label>
                    <input type="password" class="form-control <?= session('errors.userPassword') ? 'is-invalid' : '' ?>" id="userPassword" name="userPassword" value="<?= old('userPassword', '') ?>">
                </div>
            <?php endif; ?>
            <div class="mb-3">
                <label for="firstName" class="form-label"><?= lang('Forms.profile.first_name') ?></label>
                <input type="text" class="form-control <?= session('errors.firstName') ? 'is-invalid' : '' ?>" id="firstName" name="firstName" value="<?= old('firstName', esc($profileModel->first_name)) ?>" <?= $action == ProfileController::ACTION_SHOW ? 'disabled' : '' ?>>
            </div>
            <div class="mb-3">
                <label for="lastName" class="form-label"><?= lang('Forms.profile.last_name') ?></label>
                <input type="text" class="form-control <?= session('errors.lastName') ? 'is-invalid' : '' ?>" id="lastName" name="lastName" value="<?= old('lastName', esc($profileModel->last_name)) ?>" <?= $action == ProfileController::ACTION_SHOW ? 'disabled' : '' ?>>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label"><?= lang('Forms.profile.description') ?></label>
                <textarea class="form-control <?= session('errors.description') ? 'is-invalid' : '' ?>" id="description" name="description" rows="6" <?= $action == ProfileController::ACTION_SHOW ? 'disabled' : '' ?>><?= old('description', esc($profileModel->description)) ?></textarea>
            </div>
        </div>

        <div class="col-md-4 text-center">
            <div class="profile-placeholder">
                <img id="photoPreview" src="<?= esc($profileModel->getPhoto()) ?>" alt="Фото профиля" class="profile-img">
            </div>
            <?php if (in_array($action, [ProfileController::ACTION_ADD, ProfileController::ACTION_EDIT])) : ?>
                <div>
                    <input type="file" class="form-control d-none" id="photoLink" name="profileImage" accept="image/*">
                    <button type="button" class="btn btn-secondary mt-2" onclick="document.getElementById('photoLink').click()"><?= lang('Buttons.upload_photo') ?></button>
                </div>
            <?php else: ?>
                <div class="button-container">
                    <a class="btn btn-success export-excel" href="<?= base_url('/profile/export/excel/' . $userModel->id) ?>" role="button"><?= lang('Buttons.export.xlsx') ?></a>
                    <a class="btn btn-success export-excel" href="<?= base_url('/profile/export/word/' . $userModel->id) ?>" role="button"><?= lang('Buttons.export.docx') ?></a>
                    <a class="btn btn-primary edit" href="<?= base_url('/profile/edit/' . $userModel->id) ?>" role="button"><?= lang('Buttons.edit') ?></a>
                    <?php if (auth()->user()->inGroup('admin') && !$userModel->inGroup('admin')) : ?>
                        <a href="<?= base_url('/profile/toggle-status/' . $userModel->id) ?>" class="btn btn-warning"><?= $userModel->active ? lang('Buttons.deactivate') : lang('Buttons.activate') ?></a>
                        <a class="btn btn-danger delete" href="<?= base_url('profile/delete/' . $userModel->id) ?>" role="button"><?= lang('Buttons.delete') ?></a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (in_array($action, [ProfileController::ACTION_ADD, ProfileController::ACTION_EDIT])) : ?>
    <div class="control-container">
        <button type="submit" class="btn btn-primary mt-3 load"><?= lang('Buttons.save') ?></button>
        <?php if ($action == ProfileController::ACTION_EDIT) : ?>
            <a class="btn btn-danger" href="<?= base_url('/profile/' . $userModel->id) ?>" role="button"><?= lang('Buttons.back') ?></a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</form>

<!-- Modal window for cropping photos -->
<?php if (in_array($action, [ProfileController::ACTION_ADD, ProfileController::ACTION_EDIT])) : ?>
    <div class="modal fade" id="cropperModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= lang('Titles.cropper') ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <canvas id="cropCanvas"></canvas>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= lang('Buttons.cancellation') ?></button>
                    <button type="button" class="btn btn-primary" id="cropSave"><?= lang('Buttons.save') ?></button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>




