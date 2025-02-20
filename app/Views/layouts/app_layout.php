<?php

use App\Helpers\MenuHelper;

?>

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
    <!-- Боковое меню -->
    <nav class="col-md-2 bg-dark text-light vh-100 d-flex flex-column p-3 sidebar">
        <h4 class="app-name text-center">UserAdmin</h4>
        <h4 class="mini-app-name text-center">UA</h4>
        <hr>
        <ul class="nav flex-column mt-3 nav-pills">
            <?php foreach (MenuHelper::getMenuItems() as $item) : ?>
                <?php if (!isset($item['roles']) || auth()->user()->inGroup(...$item['roles'])) : ?>
                    <li class="nav-item">
                        <a class="nav-link text-light <?= (service('request')->getUri()->getPath() == $item['path']) ? 'active disabled' : '' ?>"
                           href="<?= site_url($item['path']) ?>"
                        >
                            <?= $item['icon'] ?>
                            <span><?= $item['title'] ?></span>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>

        <div class="mini-menu">
            <?php foreach (MenuHelper::getMenuItems() as $item) : ?>
                <a class="nav-link text-center <?= (service('request')->getUri()->getPath() == $item['path']) ? 'active disabled' : '' ?>"
                    href="<?= site_url($item['path']) ?>"
                >
                    <?= $item['icon'] ?>
                </a>
            <?php endforeach; ?>
        </div>
        <hr>
        <a class="logout-btn text-center" href="<?= site_url('logout') ?>">
            <i class='bx bx-log-out-circle'></i>
            <span>Выйти<span>
        </a>
    </nav>

    <!-- Основной контент -->
    <main>
        <?= $this->renderSection('content') ?>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>