<?= $this->extend('layouts/app_layout') ?>

<?= $this->section('title') ?>
UACi | <?= lang('Titles.history') ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<link href="<?= base_url('assets/css/history/style.css?v=' . time()) ?>" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="<?= base_url('assets/js/history/index.js?v=' . time()) ?>"></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<h2 class="mb-4"><?= lang('Titles.history') ?></h2>

<div class="alert-container"></div>

<table id="historyTable" class="table table-striped table-bordered">
    <thead class="table-black">
    <tr>
        <th>User</th>
        <th>Action</th>
        <th>Model Name</th>
        <th>Model Id</th>
        <th>Old Data</th>
        <th>New Data</th>
        <th>IP</th>
        <th>Date</th>
    </tr>
    </thead>
</table>
<?= $this->endSection() ?>
