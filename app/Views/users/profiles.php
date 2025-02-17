<?= $this->extend('layouts/app_layout') ?>

<?= $this->section('title') ?>
    UACi | Все профили
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<h2 class="mb-4">Редактирование профиля</h2>

<form id="profileForm" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <input type="hidden" name="_method" value="PUT">
    <div class="mb-3 text-center">
        <img id="photoPreview" src="" alt="Фото профиля" class="profile-img">
        <div class="mt-2">
            <input type="file" class="form-control" id="photoLink" name="profile_image" accept="image/*">
        </div>
    </div>
    <div class="mb-3">
        <label for="firstName" class="form-label">Имя</label>
        <input type="text" class="form-control" id="firstName" name="first_name" value="<?= esc($user['first_name'] ?? '') ?>" required>
    </div>
    <div class="mb-3">
        <label for="lastName" class="form-label">Фамилия</label>
        <input type="text" class="form-control" id="lastName" name="last_name" value="<?= esc($user['last_name'] ?? '') ?>" required>
    </div>
    <div class="mb-3">
        <label for="nickname" class="form-label">Никнейм</label>
        <input type="text" class="form-control" id="userName" name="nickname" value="<?= esc($user['nickname'] ?? '') ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Сохранить</button>
</form>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const imageInput = document.getElementById("photoLink");
        const photoPreview = document.getElementById("photoPreview");

        imageInput.addEventListener("change", function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    photoPreview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    });
</script>
<?= $this->endSection() ?>


<?php /*= $this->section('styles') */?><!--
<link href="<?php /*= base_url('assets/css/profile.css?v=' . time()) */?>" rel="stylesheet">
<?php /*= $this->endSection() */?>

<?php /*= $this->section('scripts') */?>
<script src="<?php /*= base_url('assets/js/profile.js?v=' . time()) */?>"></script>
<?php /*= $this->endSection() */?>

