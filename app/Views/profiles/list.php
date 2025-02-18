<?= $this->extend('layouts/app_layout') ?>

<?= $this->section('title') ?>
    UACi | Все профили
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<h2 class="mb-4">Список пользователей</h2>

<div class="row">
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm p-3">
                <div class="d-flex align-items-center justify-content-between">

                    <div>
                        <h5 class="card-title mb-1"> <?/*//= esc($users['nickname']) */?> </h5>
                        <p class="card-text text-muted"> <?/*//= esc(mb_strimwidth($users['description'], 0, 80, '...')) */?> </p>
                    </div>


                    <div class="text-center">
                        <img src="<?/*//= esc($users['profile_image'] ?? '/assets/images/default-avatar.png') */?>" alt="Фото" class="rounded-circle" width="80" height="80">
                        <div class="mt-2">
                            <a href="<?/*//= site_url('users/view/' . $users['id']) */?>" class="btn btn-info btn-sm">👁 Просмотреть</a>
                            <a href="<?/*//= site_url('users/edit/' . $users['id']) */?>" class="btn btn-warning btn-sm">✏ Редактировать</a>
                            <button class="btn btn-danger btn-sm" onclick="deleteUser(<?/*//= $users['id'] */?>)">🗑 Удалить</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
<?= $this->endSection() ?>
