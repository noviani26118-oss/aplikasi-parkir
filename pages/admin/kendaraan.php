<?php
require_once '../../config/database.php';
require_once '../../functions/helpers.php';
check_login();
check_role(['admin']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'add') {
            $nama = $_POST['nama_kendaraan'];
            $query = "INSERT INTO tabel_kendaraan (nama_kendaraan) VALUES ('$nama')";
            if (mysqli_query($conn, $query)) {
                log_activity("Menambah jenis kendaraan: $nama");
                set_flash_message('success', 'Kendaraan berhasil ditambahkan');
            } else {
                set_flash_message('danger', 'Gagal: ' . mysqli_error($conn));
            }
            
        } elseif ($_POST['action'] == 'edit') {
            $id = $_POST['id_kendaraan'];
            $nama = $_POST['nama_kendaraan'];
            $query = "UPDATE tabel_kendaraan SET nama_kendaraan='$nama' WHERE id_kendaraan='$id'";
            if (mysqli_query($conn, $query)) {
                log_activity("Mengupdate jenis kendaraan ID: $id ($nama)");
                set_flash_message('success', 'Kendaraan diupdate');
            } else {
                set_flash_message('danger', 'Gagal: ' . mysqli_error($conn));
            }

        } elseif ($_POST['action'] == 'delete') {
            $id = $_POST['id_kendaraan'];
            $query = "DELETE FROM tabel_kendaraan WHERE id_kendaraan='$id'";
            if (mysqli_query($conn, $query)) {
                log_activity("Menghapus jenis kendaraan ID: $id");
                set_flash_message('success', 'Kendaraan dihapus');
            } else {
                set_flash_message('danger', 'Gagal: ' . mysqli_error($conn));
            }
        }
    }
}
?>

<?php include '../../layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Master Kendaraan</h1>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAdd">
        <i class="fas fa-plus"></i> Tambah Kendaraan
    </button>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Kendaraan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = mysqli_query($conn, "SELECT * FROM tabel_kendaraan");
                    while ($row = mysqli_fetch_assoc($result)) :
                    ?>
                    <tr>
                        <td><?= $row['id_kendaraan'] ?></td>
                        <td><?= $row['nama_kendaraan'] ?></td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id_kendaraan'] ?>"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalDelete<?= $row['id_kendaraan'] ?>"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    
                    <!-- Edit Modal -->
                    <div class="modal fade" id="modalEdit<?= $row['id_kendaraan'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <form method="POST">
                                <div class="modal-content">
                                    <div class="modal-header"><h5 class="modal-title">Edit Kendaraan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                    <div class="modal-body">
                                        <input type="hidden" name="action" value="edit">
                                        <input type="hidden" name="id_kendaraan" value="<?= $row['id_kendaraan'] ?>">
                                        <div class="mb-3"><label>Nama Kendaraan</label><input type="text" class="form-control" name="nama_kendaraan" value="<?= $row['nama_kendaraan'] ?>" required></div>
                                    </div>
                                    <div class="modal-footer"><button type="submit" class="btn btn-primary">Simpan</button></div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Delete Modal -->
                    <div class="modal fade" id="modalDelete<?= $row['id_kendaraan'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <form method="POST">
                                <div class="modal-content">
                                    <div class="modal-header"><h5 class="modal-title">Hapus Kendaraan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                    <div class="modal-body">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id_kendaraan" value="<?= $row['id_kendaraan'] ?>">
                                        <p>Hapus kendaraan <strong><?= $row['nama_kendaraan'] ?></strong>?</p>
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
                <div class="modal-header"><h5 class="modal-title">Tambah Kendaraan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3"><label>Nama Kendaraan</label><input type="text" class="form-control" name="nama_kendaraan" required></div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Simpan</button></div>
            </div>
        </form>
    </div>
</div>

<?php include '../../layouts/footer.php'; ?>
