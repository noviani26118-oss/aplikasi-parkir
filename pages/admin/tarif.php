<?php
require_once '../../config/database.php';
require_once '../../functions/helpers.php';
check_login();
check_role(['admin']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'add') {
            $id_kendaraan = $_POST['id_kendaraan'];
            $tarif = $_POST['tarif_per_jam'];
            $query = "INSERT INTO tabel_tarif (id_kendaraan, tarif_per_jam) VALUES ('$id_kendaraan', '$tarif')";
            if (mysqli_query($conn, $query)) {
                log_activity("Menambah tarif baru untuk ID Kendaraan: $id_kendaraan (Tarif: $tarif)");
                set_flash_message('success', 'Tarif berhasil ditambahkan');
            } else {
                set_flash_message('danger', 'Gagal: ' . mysqli_error($conn));
            }
            
        } elseif ($_POST['action'] == 'edit') {
            $id = $_POST['id_tarif'];
            $id_kendaraan = $_POST['id_kendaraan'];
            $tarif = $_POST['tarif_per_jam'];
            $query = "UPDATE tabel_tarif SET id_kendaraan='$id_kendaraan', tarif_per_jam='$tarif' WHERE id_tarif='$id'";
            if (mysqli_query($conn, $query)) {
                log_activity("Mengupdate tarif ID: $id (ID Kendaraan: $id_kendaraan, Tarif: $tarif)");
                set_flash_message('success', 'Tarif diupdate');
            } else {
                set_flash_message('danger', 'Gagal: ' . mysqli_error($conn));
            }

        } elseif ($_POST['action'] == 'delete') {
            $id = $_POST['id_tarif'];
            $query = "DELETE FROM tabel_tarif WHERE id_tarif='$id'";
            if (mysqli_query($conn, $query)) {
                log_activity("Menghapus tarif ID: $id");
                set_flash_message('success', 'Tarif dihapus');
            } else {
                set_flash_message('danger', 'Gagal: ' . mysqli_error($conn));
            }
        }
    }
}

// Get Vehicle Data for Dropdown
$kendaraan_res = mysqli_query($conn, "SELECT * FROM tabel_kendaraan");
$kendaraan_data = [];
while($k = mysqli_fetch_assoc($kendaraan_res)) {
    $kendaraan_data[] = $k;
}
?>

<?php include '../../layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Kelola Tarif Parkir</h1>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAdd">
        <i class="fas fa-plus"></i> Tambah Tarif
    </button>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Jenis Kendaraan</th>
                        <th>Tarif Per Jam (Rp)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = mysqli_query($conn, "SELECT t.*, k.nama_kendaraan FROM tabel_tarif t JOIN tabel_kendaraan k ON t.id_kendaraan = k.id_kendaraan");
                    while ($row = mysqli_fetch_assoc($result)) :
                    ?>
                    <tr>
                        <td><?= $row['id_tarif'] ?></td>
                        <td><?= $row['nama_kendaraan'] ?></td>
                        <td>Rp <?= number_format($row['tarif_per_jam'], 0, ',', '.') ?></td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id_tarif'] ?>"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalDelete<?= $row['id_tarif'] ?>"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    
                    <!-- Edit Modal -->
                    <div class="modal fade" id="modalEdit<?= $row['id_tarif'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <form method="POST">
                                <div class="modal-content">
                                    <div class="modal-header"><h5 class="modal-title">Edit Tarif</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                    <div class="modal-body">
                                        <input type="hidden" name="action" value="edit">
                                        <input type="hidden" name="id_tarif" value="<?= $row['id_tarif'] ?>">
                                        <div class="mb-3">
                                            <label>Kendaraan</label>
                                            <select class="form-select" name="id_kendaraan">
                                                <?php foreach($kendaraan_data as $k): ?>
                                                    <option value="<?= $k['id_kendaraan'] ?>" <?= $k['id_kendaraan'] == $row['id_kendaraan'] ? 'selected' : '' ?>>
                                                        <?= $k['nama_kendaraan'] ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="mb-3"><label>Tarif Per Jam</label><input type="number" class="form-control" name="tarif_per_jam" value="<?= $row['tarif_per_jam'] ?>" required></div>
                                    </div>
                                    <div class="modal-footer"><button type="submit" class="btn btn-primary">Simpan</button></div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Delete Modal -->
                    <div class="modal fade" id="modalDelete<?= $row['id_tarif'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <form method="POST">
                                <div class="modal-content">
                                    <div class="modal-header"><h5 class="modal-title">Hapus Tarif</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                    <div class="modal-body">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id_tarif" value="<?= $row['id_tarif'] ?>">
                                        <p>Hapus tarif ini?</p>
                                    </div>
                                    <div class="modal-footer"><button type="submit" class="btn btn-danger">Hapus</button></div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="modalAdd" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Tambah Tarif</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label>Kendaraan</label>
                        <select class="form-select" name="id_kendaraan">
                            <?php foreach($kendaraan_data as $k): ?>
                                <option value="<?= $k['id_kendaraan'] ?>"><?= $k['nama_kendaraan'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3"><label>Tarif Per Jam</label><input type="number" class="form-control" name="tarif_per_jam" required></div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Simpan</button></div>
            </div>
        </form>
    </div>
</div>

<?php include '../../layouts/footer.php'; ?>
