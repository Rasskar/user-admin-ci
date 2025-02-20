<?php

/**
 * @var User[] $users
 * @var Pager|null $pager
 * @var string $search
 */

use CodeIgniter\Pager\Pager;
use CodeIgniter\Shield\Entities\User;

?>

<?= $this->extend('layouts/app_layout') ?>

<?= $this->section('title') ?>
    UACi | Все профили
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link href="<?= base_url('assets/css/profiles/list.css?v=' . time()) ?>" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<h2 class="mb-4">Список пользователей</h2>

<form id="searchForm" method="GET" action="<?= site_url('profiles') ?>" class="mb-3">
    <div class="row">
        <input type="text" name="search" class="form-control" value="<?= esc($search ?? '') ?>" placeholder="Поиск по имени или email">
        <button type="submit" class="btn btn-primary">Поиск</button>
    </div>
</form>

<div class="row users-list">
    <table class="table">
        <thead class="table-black">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Name User</th>
                <th class="email" scope="col">Email</th>
                <th class="status" scope="col">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user) : ?>
                <tr class="<?= $user->status ?>">
                    <th scope="row"><?= $user->id ?></th>
                    <td>
                        <a href="<?= site_url('profile/' . $user->id) ?>">
                            <?= $user->username ?>
                        </a>
                    </td>
                    <td class="email"><?= $user->email ?></td>
                    <td class="status"><?= $user->status ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?= $pager->links('default', 'pagination_custom') ?>
</div>
<?= $this->endSection() ?>
