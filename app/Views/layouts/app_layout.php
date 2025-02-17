<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="<?= base_url('assets/css/layout.css?v=' . time()) ?>" rel="stylesheet">
    <?= $this->renderSection('styles') ?>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Боковое меню -->
        <nav class="col-md-2 bg-dark text-light vh-100 d-flex flex-column p-3 sidebar">
            <h4 class="app-name text-center">UserAdminCi</h4>
            <h4 class="mini-app-name text-center">UACi</h4>
            <hr>
            <ul class="nav flex-column mt-3 nav-pills">
                <li class="nav-item">
                    <a class="nav-link text-light active" href="<?= site_url('profile') ?>">
                        <i class='bx bxs-user'></i>
                        <span>Мой профиль</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="<?= site_url('users') ?>">
                        <i class='bx bx-list-ul'></i>
                        <span>Все профили</span>
                    </a>
                </li>
            </ul>

            <div class="mini-menu">
                <a class="nav-link text-center active" href="<?= site_url('profile') ?>">
                    <i class='bx bxs-user'></i>
                </a>
                <a class="nav-link text-center" href="<?= site_url('users') ?>">
                    <i class='bx bx-list-ul'></i>
                </a>
            </div>

            <hr>
            <a class="logout-btn text-center" href="<?= site_url('logout') ?>">
                <i class='bx bx-log-out-circle'></i>
                <span>Выйти<span>
            </a>
        </nav>

        <!-- Основной контент -->
        <main class="col-md-10 p-4">
            <?= $this->renderSection('content') ?>
        </main>
    </div>
</div>

<?= $this->renderSection('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>