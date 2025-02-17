<?= $this->extend('layouts/app_layout') ?>

<?= $this->section('title') ?>
UACi | Профиль пользователя
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<?= $this->endSection() ?>



<?= $this->section('content') ?>
<h2 class="mb-4">Список пользователей</h2>

<div class="row">
    <?//php foreach ($users as $user): ?>
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <!-- Левая часть: информация -->
                    <div>
                        <h5 class="card-title mb-1"> <?//= esc($user['nickname']) ?> </h5>
                        <p class="card-text text-muted"> <?//= esc(mb_strimwidth($user['description'], 0, 80, '...')) ?> </p>
                    </div>

                    <!-- Правая часть: фото профиля и кнопки -->
                    <div class="text-center">
                        <img src="<?//= esc($user['profile_image'] ?? '/assets/images/default-avatar.png') ?>" alt="Фото" class="rounded-circle" width="80" height="80">
                        <div class="mt-2">
                            <a href="<?//= site_url('users/view/' . $user['id']) ?>" class="btn btn-info btn-sm">👁 Просмотреть</a>
                            <a href="<?//= site_url('users/edit/' . $user['id']) ?>" class="btn btn-warning btn-sm">✏ Редактировать</a>
                            <button class="btn btn-danger btn-sm" onclick="deleteUser(<?//= $user['id'] ?>)">🗑 Удалить</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?//php endforeach; ?>
</div>

<!-- Пагинация -->
<nav aria-label="Page navigation example">
    <ul class="pagination">
        <li class="page-item">
            <a class="page-link" href="#" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
        <li class="page-item"><a class="page-link" href="#">1</a></li>
        <li class="page-item"><a class="page-link" href="#">2</a></li>
        <li class="page-item"><a class="page-link" href="#">3</a></li>
        <li class="page-item">
            <a class="page-link" href="#" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
</nav>




<h2 class="mb-4">Профиль пользователя</h2>

<!-- Блок уведомлений -->
<div id="alertBox" class="alert d-none" role="alert"></div>

<form id="profileForm" enctype="multipart/form-data" method="POST" <?= isset($isViewMode) && $isViewMode ? 'style="display: none;"' : '' ?>>
    <?= csrf_field() ?>
    <div class="row">
        <!-- Левая колонка с текстовыми полями -->
        <div class="col-md-8">
            <div class="mb-3">
                <label for="userRole" class="form-label">Роль</label>
                <select class="form-control" id="userRole" name="role" <?= isset($isViewMode) && $isViewMode ? 'disabled' : '' ?>>
                    <option value="user" <?= (isset($user['role']) && $user['role'] == 'user') ? 'selected' : '' ?>>Пользователь</option>
                    <option value="admin" <?= (isset($user['role']) && $user['role'] == 'admin') ? 'selected' : '' ?>>Администратор</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="firstName" class="form-label">Имя</label>
                <input type="text" class="form-control" id="firstName" name="first_name" value="<?= esc($user['first_name'] ?? '') ?>" required <?= isset($isViewMode) && $isViewMode ? 'readonly' : '' ?>>
            </div>
            <div class="mb-3">
                <label for="lastName" class="form-label">Фамилия</label>
                <input type="text" class="form-control" id="lastName" name="last_name" value="<?= esc($user['last_name'] ?? '') ?>" required <?= isset($isViewMode) && $isViewMode ? 'readonly' : '' ?>>
            </div>
            <div class="mb-3">
                <label for="userName" class="form-label">Никнейм</label>
                <input type="text" class="form-control" id="userName" name="nickname" value="<?= esc($user['nickname'] ?? '') ?>" required <?= isset($isViewMode) && $isViewMode ? 'readonly' : '' ?>>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Описание</label>
                <textarea class="form-control" id="description" name="description" rows="3" <?= isset($isViewMode) && $isViewMode ? 'readonly' : '' ?>><?= esc($user['description'] ?? '') ?></textarea>
            </div>
        </div>

        <!-- Правая колонка с фото -->
        <div class="col-md-4 text-center">
            <div class="profile-placeholder">
                <img id="photoPreview" src="<?= esc($user['profile_image'] ?? '/assets/images/default-avatar.png') ?>" alt="Фото профиля" class="profile-img">
            </div>
            <?php if (!isset($isViewMode) || !$isViewMode): ?>
                <div>
                    <input type="file" class="form-control d-none" id="photoLink" name="profile_image" accept="image/*">
                    <button type="button" class="btn btn-secondary mt-2" onclick="document.getElementById('photoLink').click()">Загрузить фотографию</button>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!isset($isViewMode) || !$isViewMode): ?>
        <button type="submit" class="btn btn-primary mt-3">Сохранить</button>
    <?php endif; ?>
</form>

<!-- Модальное окно для обрезки фото -->
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

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let cropper;
        const imageInput = document.getElementById("photoLink");
        const photoPreview = document.getElementById("photoPreview");
        const cropModal = new bootstrap.Modal(document.getElementById("cropperModal"));
        const cropSave = document.getElementById("cropSave");

        imageInput.addEventListener("change", function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    let img = document.createElement("img");
                    img.src = e.target.result;
                    img.id = "cropImage";
                    img.style.maxWidth = "100%";

                    let modalBody = document.querySelector("#cropperModal .modal-body");
                    modalBody.innerHTML = "";
                    modalBody.appendChild(img);

                    cropper = new Cropper(img, {
                        aspectRatio: 1,
                        viewMode: 2,
                    });

                    cropModal.show();
                };
                reader.readAsDataURL(file);
            }
        });

        cropSave.addEventListener("click", function () {
            if (cropper) {
                let canvas = cropper.getCroppedCanvas({ width: 150, height: 150 });
                canvas.toBlob((blob) => {
                    let url = URL.createObjectURL(blob);
                    photoPreview.src = url;
                    let croppedFile = new File([blob], "profile.jpg", { type: "image/jpeg" });
                    let dataTransfer = new DataTransfer();
                    dataTransfer.items.add(croppedFile);
                    imageInput.files = dataTransfer.files;
                });
                cropModal.hide();
            }
        });
    });
</script>
<?= $this->endSection() ?>




