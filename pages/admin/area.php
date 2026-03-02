<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../functions/helpers.php';
check_login();
check_role(['admin']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'add') {
            $nama = $_POST['nama_area'];
            $kapasitas = $_POST['kapasitas'];
            $query = "INSERT INTO tabel_area_parkir (nama_area, kapasitas) VALUES ('$nama', '$kapasitas')";
            if (mysqli_query($conn, $query)) {
                log_activity("Menambah area parkir: $nama");
                set_flash_message('success', 'Area berhasil ditambahkan');
            } else {
                set_flash_message('danger', 'Gagal: ' . mysqli_error($conn));
            }
            
        } elseif ($_POST['action'] == 'edit') {
            $id = $_POST['id_area'];
            $nama = $_POST['nama_area'];
            $kapasitas = $_POST['kapasitas'];
            $query = "UPDATE tabel_area_parkir SET nama_area='$nama', kapasitas='$kapasitas' WHERE id_area='$id'";
            if (mysqli_query($conn, $query)) {
                log_activity("Mengupdate area parkir ID: $id ($nama)");
                set_flash_message('success', 'Area diupdate');
            } else {
                set_flash_message('danger', 'Gagal: ' . mysqli_error($conn));
            }

        } elseif ($_POST['action'] == 'delete') {
            $id = $_POST['id_area'];
            $query = "DELETE FROM tabel_area_parkir WHERE id_area='$id'";
            if (mysqli_query($conn, $query)) {
                log_activity("Menghapus area parkir ID: $id");
                set_flash_message('success', 'Area dihapus');
            } else {
                set_flash_message('danger', 'Gagal: ' . mysqli_error($conn));
            }
        }
    }
}
?>

<?php include __DIR__ . '/../../layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Kelola Area Parkir</h1>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAdd">
        <i class="fas fa-plus"></i> Tambah Area
    </button>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Area</th>
                        <th>Kapasitas</th>
                        <th>Terisi</th>
                        <th>Tersedia</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = mysqli_query($conn, "SELECT * FROM tabel_area_parkir");
                    while ($row = mysqli_fetch_assoc($result)) :
                    ?>
                    <tr>
                        <td><?= $row['id_area'] ?></td>
                        <td><?= $row['nama_area'] ?></td>
                        <td><?= $row['kapasitas'] ?></td>
                        <td><?= $row['terisi'] ?></td>
                        <td><?= $row['kapasitas'] - $row['terisi'] ?></td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id_area'] ?>"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalDelete<?= $row['id_area'] ?>"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    
                    <!-- Edit Modal -->
                    <div class="modal fade" id="modalEdit<?= $row['id_area'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <form method="POST">
                                <div class="modal-content">
                                    <div class="modal-header"><h5 class="modal-title">Edit Area</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                    <div class="modal-body">
                                        <input type="hidden" name="action" value="edit">
                                        <input type="hidden" name="id_area" value="<?= $row['id_area'] ?>">
                                        <div class="mb-3"><label>Nama Area</label><input type="text" class="form-control" name="nama_area" value="<?= $row['nama_area'] ?>" required></div>
                                        <div class="mb-3"><label>Kapasitas</label><input type="number" class="form-control" name="kapasitas" value="<?= $row['kapasitas'] ?>" required></div>
                                    </div>
                                    <div class="modal-footer"><button type="submit" class="btn btn-primary">Simpan</button></div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Delete Modal -->
                    <div class="modal fade" id="modalDelete<?= $row['id_area'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <form method="POST">
                                <div class="modal-content">
                                    <div class="modal-header"><h5 class="modal-title">Hapus Area</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                    <div class="modal-body">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id_area" value="<?= $row['id_area'] ?>">
                                        <p>Hapus area <strong><?= $row['nama_area'] ?></strong>?</p>
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
                <div class="modal-header"><h5 class="modal-title">Tambah Area</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3"><label>Nama Area</label><input type="text" class="form-control" name="nama_area" required></div>
                    <div class="mb-3"><label>Kapasitas</label><input type="number" class="form-control" name="kapasitas" required></div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Simpan</button></div>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
