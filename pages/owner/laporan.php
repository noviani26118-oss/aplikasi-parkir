<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../functions/helpers.php';
check_login();
check_role(['owner', 'admin']); // Admin also might want to see reports

$tgl_awal = $_GET['tgl_awal'] ?? date('Y-m-01');
$tgl_akhir = $_GET['tgl_akhir'] ?? date('Y-m-d');

?>

<?php include __DIR__ . '/../../layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Laporan Pendapatan Parkir</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">
            <i class="fas fa-print"></i> Cetak Laporan
        </button>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <!-- Filter Form -->
        <form action="" method="GET" class="row g-3 mb-4 no-print">
            <div class="col-auto">
                <label class="col-form-label">Dari Tanggal</label>
            </div>
            <div class="col-auto">
                <input type="date" class="form-control" name="tgl_awal" value="<?= $tgl_awal ?>">
            </div>
            <div class="col-auto">
                <label class="col-form-label">Sampai Tanggal</label>
            </div>
            <div class="col-auto">
                <input type="date" class="form-control" name="tgl_akhir" value="<?= $tgl_akhir ?>">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode TRX</th>
                        <th>Plat Nomor</th>
                        <th>Kendaraan</th>
                        <th>Masuk</th>
                        <th>Keluar</th>
                        <th>Lama (Jam)</th>
                        <th>Total Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT t.*, k.nama_kendaraan 
                              FROM tabel_transaksi t 
                              JOIN tabel_kendaraan k ON t.id_kendaraan = k.id_kendaraan
                              WHERE t.status = 'keluar' 
                              AND DATE(t.jam_keluar) BETWEEN '$tgl_awal' AND '$tgl_akhir'
                              ORDER BY t.jam_keluar DESC";
                    $result = mysqli_query($conn, $query);
                    $total_pendapatan = 0;
                    $no = 1;
                    
                    while ($row = mysqli_fetch_assoc($result)) :
                        $total_pendapatan += $row['total_bayar'];
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $row['kode_transaksi'] ?></td>
                        <td><?= $row['plat_nomor'] ?></td>
                        <td><?= $row['nama_kendaraan'] ?></td>
                        <td><?= $row['jam_masuk'] ?></td>
                        <td><?= $row['jam_keluar'] ?></td>
                        <td><?= $row['lama_parkir'] ?></td>
                        <td class="text-end">Rp <?= number_format($row['total_bayar'], 0, ',', '.') ?></td>
                    </tr>
                    <?php endwhile; ?>
                    
                    <?php if (mysqli_num_rows($result) > 0): ?>
                    <tr class="table-dark fw-bold">
                        <td colspan="7" class="text-center">TOTAL PENDAPATAN</td>
                        <td class="text-end">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></td>
                    </tr>
                    <?php else: ?>
                    <tr><td colspan="8" class="text-center">Tidak ada data transaksi pada periode ini.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    @media print {
        .no-print { display: none !important; }
        .sidebar { display: none !important; }
        main { width: 100% !important; margin: 0 !important; padding: 0 !important; }
    }
</style>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
