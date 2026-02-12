<?php
require_once '../../config/database.php';
require_once '../../functions/helpers.php';
check_login();
check_role(['petugas']);
?>

<?php include '../../layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard Petugas</h1>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4 shadow-sm">
            <div class="card-body text-center">
                <h3 class="card-title"><i class="fas fa-sign-in-alt fa-2x text-primary mb-3"></i></h3>
                <a href="parkir_masuk.php" class="btn btn-lg btn-primary w-100">Catat Kendaraan Masuk</a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-4 shadow-sm">
            <div class="card-body text-center">
                <h3 class="card-title"><i class="fas fa-sign-out-alt fa-2x text-danger mb-3"></i></h3>
                <a href="parkir_keluar.php" class="btn btn-lg btn-danger w-100">Proses Kendaraan Keluar</a>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">Kendaraan Terparkir Saat Ini</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th>Plat Nomor</th>
                        <th>Jenis</th>
                        <th>Masuk</th>
                        <th>Area</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Display last 5 parked vehicles
                    $query = "SELECT t.*, k.nama_kendaraan, a.nama_area 
                              FROM tabel_transaksi t 
                              JOIN tabel_kendaraan k ON t.id_kendaraan = k.id_kendaraan
                              JOIN tabel_area_parkir a ON t.id_area = a.id_area
                              WHERE t.status = 'masuk'
                              ORDER BY t.jam_masuk DESC LIMIT 5";
                    $result = mysqli_query($conn, $query);
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['plat_nomor'] . "</td>";
                        echo "<td>" . $row['nama_kendaraan'] . "</td>";
                        echo "<td>" . $row['jam_masuk'] . "</td>";
                        echo "<td>" . $row['nama_area'] . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../../layouts/footer.php'; ?>
