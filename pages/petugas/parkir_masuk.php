<?php
require_once '../../config/database.php';
require_once '../../functions/helpers.php';
check_login();
check_role(['petugas']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode_transaksi = "TRX-" . date("YmdHis");
    $plat_nomor = strtoupper($_POST['plat_nomor']);
    $id_kendaraan = $_POST['id_kendaraan'];
    $id_area = $_POST['id_area'];
    $id_user = $_SESSION['user']['id_user'];
    $jam_masuk = date("Y-m-d H:i:s");

    // Check Capacity
    $area_query = mysqli_query($conn, "SELECT * FROM tabel_area_parkir WHERE id_area = '$id_area'");
    $area = mysqli_fetch_assoc($area_query);

    if ($area['terisi'] >= $area['kapasitas']) {
        set_flash_message('danger', 'Area parkir penuh!');
    } else {
        $query = "INSERT INTO tabel_transaksi (kode_transaksi, id_kendaraan, id_area, id_user, plat_nomor, jam_masuk, status, tanggal_transaksi) 
                  VALUES ('$kode_transaksi', '$id_kendaraan', '$id_area', '$id_user', '$plat_nomor', '$jam_masuk', 'masuk', CURDATE())";
        
        if (mysqli_query($conn, $query)) {
            // Update Area Capacity
            mysqli_query($conn, "UPDATE tabel_area_parkir SET terisi = terisi + 1 WHERE id_area = '$id_area'");
            log_activity("Mencatat kendaraan masuk: $plat_nomor (Kode: $kode_transaksi)");
            set_flash_message('success', 'Kendaraan berhasil masuk. Kode: <b>' . $kode_transaksi . '</b>');
        } else {
            set_flash_message('danger', 'Gagal mencatat transaksi: ' . mysqli_error($conn));
        }
    }
}
?>

<?php include '../../layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Parkir Masuk</h1>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">Form Kendaraan Masuk</div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Plat Nomor</label>
                        <input type="text" class="form-control" name="plat_nomor" placeholder="B 1234 XY" required uppercase>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis Kendaraan</label>
                        <select class="form-select" name="id_kendaraan" required>
                            <?php
                            $vk = mysqli_query($conn, "SELECT * FROM tabel_kendaraan");
                            while($k = mysqli_fetch_assoc($vk)) {
                                echo "<option value='{$k['id_kendaraan']}'>{$k['nama_kendaraan']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Area Parkir</label>
                        <select class="form-select" name="id_area" required>
                            <?php
                            $va = mysqli_query($conn, "SELECT * FROM tabel_area_parkir");
                            while($a = mysqli_fetch_assoc($va)) {
                                $sisa = $a['kapasitas'] - $a['terisi'];
                                $disabled = $sisa <= 0 ? 'disabled' : '';
                                echo "<option value='{$a['id_area']}' $disabled>{$a['nama_area']} (Sisa: $sisa)</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Simpan Masuk</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../../layouts/footer.php'; ?>
