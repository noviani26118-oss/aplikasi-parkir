<?php
require_once '../../config/database.php';
require_once '../../functions/helpers.php';
check_login();
check_role(['admin']);

// Handle Create / Edit / Delete
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'add') {
            $nama = $_POST['nama_user'];
            $username = $_POST['username'];
            $password = md5($_POST['password']);
            $role = $_POST['role'];
            
            $query = "INSERT INTO tabel_users (nama_user, username, password, role) VALUES ('$nama', '$username', '$password', '$role')";
            if (mysqli_query($conn, $query)) {
                log_activity("Menambah user baru: $username ($role)");
                set_flash_message('success', 'User berhasil ditambahkan');
            } else {
                set_flash_message('danger', 'Gagal menambahkan user: ' . mysqli_error($conn));
            }
        } elseif ($_POST['action'] == 'edit') {
            $id = $_POST['id_user'];
            $nama = $_POST['nama_user'];
            $username = $_POST['username'];
            $role = $_POST['role'];
            
            $password_query = "";
            if (!empty($_POST['password'])) {
                $password = md5($_POST['password']);
                $password_query = ", password='$password'";
            }

            $query = "UPDATE tabel_users SET nama_user='$nama', username='$username', role='$role' $password_query WHERE id_user='$id'";
            if (mysqli_query($conn, $query)) {
                log_activity("Mengupdate data user ID: $id ($username)");
                set_flash_message('success', 'User berhasil diupdate');
            } else {
                set_flash_message('danger', 'Gagal update user: ' . mysqli_error($conn));
            }
        } elseif ($_POST['action'] == 'delete') {
            $id = $_POST['id_user'];
            if ($id != $_SESSION['user']['id_user']) { // Prevent self-delete
                 $query = "DELETE FROM tabel_users WHERE id_user='$id'";
                 if (mysqli_query($conn, $query)) {
                     set_flash_message('success', 'User berhasil dihapus');
                 } else {
                     set_flash_message('danger', 'Gagal hapus user');
                 }
            } else {
                set_flash_message('danger', 'Tidak bisa menghapus akun sendiri!');
            }
        }
    }
}
?>

<?php include '../../layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Kelola Users</h1>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAdd">
        <i class="fas fa-plus"></i> Tambah User
    </button>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama User</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT * FROM tabel_users ORDER BY id_user ASC";
                    $result = mysqli_query($conn, $query);
                    while ($row = mysqli_fetch_assoc($result)) :
                    ?>
                    <tr>
                        <td><?= $row['id_user'] ?></td>
                        <td><?= $row['nama_user'] ?></td>
                        <td><?= $row['username'] ?></td>
                        <td><span class="badge bg-secondary"><?= $row['role'] ?></span></td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id_user'] ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalDelete<?= $row['id_user'] ?>">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="modalEdit<?= $row['id_user'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <form method="POST">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit User</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="action" value="edit">
                                        <input type="hidden" name="id_user" value="<?= $row['id_user'] ?>">
                                        <div class="mb-3">
                                            <label class="form-label">Nama User</label>
                                            <input type="text" class="form-control" name="nama_user" value="<?= $row['nama_user'] ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Username</label>
                                            <input type="text" class="form-control" name="username" value="<?= $row['username'] ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Password (Kosongkan jika tidak ubah)</label>
                                            <input type="password" class="form-control" name="password">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Role</label>
                                            <select class="form-select" name="role">
                                                <option value="admin" <?= $row['role']=='admin'?'selected':'' ?>>Admin</option>
                                                <option value="petugas" <?= $row['role']=='petugas'?'selected':'' ?>>Petugas</option>
                                                <option value="owner" <?= $row['role']=='owner'?'selected':'' ?>>Owner</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Delete Modal -->
                    <div class="modal fade" id="modalDelete<?= $row['id_user'] ?>" tabindex="-1">
                         <div class="modal-dialog">
                            <form method="POST">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Hapus User</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id_user" value="<?= $row['id_user'] ?>">
                                        <p>Apakah anda yakin ingin menghapus user <strong><?= $row['nama_user'] ?></strong>?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                    </div>
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
                <div class="modal-header">
                    <h5 class="modal-title">Tambah User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label class="form-label">Nama User</label>
                        <input type="text" class="form-control" name="nama_user" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select class="form-select" name="role">
                            <option value="admin">Admin</option>
                            <option value="petugas">Petugas</option>
                            <option value="owner">Owner</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include '../../layouts/footer.php'; ?>
