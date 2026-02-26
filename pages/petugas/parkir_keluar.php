<?php
require_once '../../config/database.php';
require_once '../../functions/helpers.php';
check_login();
check_role(['petugas']);

$trx = null;
$tarif_total = 0;
$lama_jam = 0;

// Search Logic
if (isset($_GET['search'])) {
    $keyword = mysqli_real_escape_string($conn, $_GET['keyword']);
    $query = "SELECT t.*, k.nama_kendaraan, f.tarif_per_jam, a.nama_area 
              FROM tabel_transaksi t
              JOIN tabel_kendaraan k ON t.id_kendaraan = k.id_kendaraan
              JOIN tabel_area_parkir a ON t.id_area = a.id_area
              LEFT JOIN tabel_tarif f ON t.id_kendaraan = f.id_kendaraan
              WHERE (t.plat_nomor = '$keyword' OR t.kode_transaksi = '$keyword') 
              AND t.status = 'masuk' 
              LIMIT 1";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $trx = mysqli_fetch_assoc($result);
        
        // Calculate Cost
        $jam_masuk = new DateTime($trx['jam_masuk']);
        $jam_keluar = new DateTime(); // Now
        $diff = $jam_keluar->diff($jam_masuk);
        $lama_jam = ceil(($diff->days * 24) + $diff->h + ($diff->i / 60)); // Round up to nearest hour
        if ($lama_jam == 0) $lama_jam = 1; // Minimum 1 hour

        $tarif_total = $lama_jam * $trx['tarif_per_jam'];
    } else {
        set_flash_message('warning', 'Data tidak ditemukan atau kendaraan sudah keluar.');
    }
}

// Process Checkout
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['process_exit'])) {
    $id_transaksi = $_POST['id_transaksi'];
    $id_area = $_POST['id_area']; // To decrease count
    $total_bayar = $_POST['total_bayar'];
    $lama_parkir = $_POST['lama_parkir'];
    $jam_keluar = date("Y-m-d H:i:s");

    $query = "UPDATE tabel_transaksi SET 
              jam_keluar = '$jam_keluar',
              lama_parkir = '$lama_parkir',
              total_bayar = '$total_bayar',
              status = 'keluar'
              WHERE id_transaksi = '$id_transaksi'";
    
    if (mysqli_query($conn, $query)) {
        mysqli_query($conn, "UPDATE tabel_area_parkir SET terisi = terisi - 1 WHERE id_area = '$id_area'");
        log_activity("Mencatat kendaraan keluar. ID Transaksi: $id_transaksi");
        // Redirect to print receipt
        echo "<script>window.location.href='cetak_struk.php?id=$id_transaksi';</script>";
        exit;
    } else {
        set_flash_message('danger', 'Gagal memproses keluar: ' . mysqli_error($conn));
    }
}
?>

<?php include '../../layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Parkir Keluar</h1>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="" method="GET">
                    <div class="input-group">
                        <input type="text" class="form-control" name="keyword" placeholder="Masukkan Plat Nomor / Kode TRX" value="<?= $_GET['keyword'] ?? '' ?>" required>
                        <button class="btn btn-primary" type="submit" name="search">Cari</button>
                    </div>
                </form>
            </div>
        </div>
        
        <?php if ($trx): ?>
        <div class="card shadow">
            <div class="card-header bg-success text-white">Konfirmasi Keluar</div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr><th>Kode Transaksi</th> <td>: <?= $trx['kode_transaksi'] ?></td></tr>
                    <tr><th>Plat Nomor</th> <td>: <?= $trx['plat_nomor'] ?></td></tr>
                    <tr><th>Jenis</th> <td>: <?= $trx['nama_kendaraan'] ?></td></tr>
                    <tr><th>Jam Masuk</th> <td>: <?= $trx['jam_masuk'] ?></td></tr>
                    <tr><th>Jam Keluar</th> <td>: <?= date("Y-m-d H:i:s") ?> (Sekarang)</td></tr>
                    <tr><th>Lama Parkir</th> <td>: <?= $lama_jam ?> Jam</td></tr>
                    <tr><th>Tarif Per Jam</th> <td>: Rp <?= number_format($trx['tarif_per_jam']) ?></td></tr>
                    <tr class="fs-4 fw-bold table-active"><th>Total Bayar</th> <td>: Rp <?= number_format($tarif_total) ?></td></tr>
                </table>

                <form method="POST">
                    <input type="hidden" name="id_transaksi" value="<?= $trx['id_transaksi'] ?>">
                    <input type="hidden" name="id_area" value="<?= $trx['id_area'] ?>">
                    <input type="hidden" name="total_bayar" value="<?= $tarif_total ?>">
                    <input type="hidden" name="lama_parkir" value="<?= $lama_jam ?>">
                    <input type="hidden" name="process_exit" value="1">
                    <button type="submit" class="btn btn-success w-100 btn-lg">Proses Pembayaran & Cetak Struk</button>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../../layouts/footer.php'; ?>
