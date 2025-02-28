<?= $this->extend('layouts/app_layout') ?>

<?= $this->section('title') ?>
    UACi | <?= lang('Titles.profiles') ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<link href="<?= base_url('assets/css/profiles/list.css?v=' . time()) ?>" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="<?= base_url('assets/js/profiles/list.js?v=' . time()) ?>"></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<h2 class="mb-4"><?= lang('Titles.profiles') ?></h2>

<div class="alert-container">
    <?php if (session()->has('success')): ?>
        <div class="alert alert-success">
            <?= esc(session('success')) ?>
        </div>
    <?php endif; ?>
</div>

<table id="usersTable" class="table table-striped table-bordered">
    <thead class="table-black">
        <tr>
            <th>ID</th>
            <th>User Name</th>
            <th>Email</th>
            <th>Status</th>
        </tr>
    </thead>
</table>
<?= $this->endSection() ?>
