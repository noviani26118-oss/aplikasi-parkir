<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../functions/helpers.php';
check_login();
check_role(['admin']);
?>

<?php include __DIR__ . '/../../layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard Admin</h1>
</div>

<div class="row">
    <!-- Summary Cards -->
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">Total User</h5>
                <p class="card-text fs-2">
                    <?php 
                    $res = mysqli_query($conn, "SELECT count(*) as total FROM tabel_users");
                    echo mysqli_fetch_assoc($res)['total'];
                    ?>
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Area Parkir</h5>
                <p class="card-text fs-2">
                    <?php 
                    $res = mysqli_query($conn, "SELECT count(*) as total FROM tabel_area_parkir");
                    echo mysqli_fetch_assoc($res)['total'];
                    ?>
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-warning text-dark">
            <div class="card-body">
                <h5 class="card-title">Kendaraan</h5>
                <p class="card-text fs-2">
                    <?php 
                    $res = mysqli_query($conn, "SELECT count(*) as total FROM tabel_kendaraan");
                    echo mysqli_fetch_assoc($res)['total'];
                    ?>
                </p>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
