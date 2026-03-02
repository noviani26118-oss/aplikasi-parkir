<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../functions/helpers.php';
check_login();
check_role(['owner']);
?>

<?php include __DIR__ . '/../../layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard Owner</h1>
</div>

<div class="row">
    <div class="col-md-12 text-center py-5">
        <h3>Selamat Datang, Owner</h3>
        <p class="lead">Silahkan akses menu laporan untuk melihat rekap pendapatan.</p>
        <a href="laporan.php" class="btn btn-primary">Lihat Laporan</a>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
