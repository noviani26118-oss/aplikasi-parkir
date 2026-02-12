<?php
require_once '../../config/database.php';
require_once '../../functions/helpers.php';
check_login();
check_role(['admin']);
?>

<?php include '../../layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Log Aktivitas</h1>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped datatable">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>User</th>
                        <th>Aktivitas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Note: Currently we are not populating this log heavily, 
                    // but structure is here for future implementation of log hooks.
                    // For now showing empty or manual logs if any.
                    $query = "SELECT l.*, u.nama_user FROM tabel_log_aktivitas l JOIN tabel_users u ON l.id_user = u.id_user ORDER BY l.waktu DESC LIMIT 100";
                    $result = mysqli_query($conn, $query);
                    while ($row = mysqli_fetch_assoc($result)) :
                    ?>
                    <tr>
                        <td><?= $row['waktu'] ?></td>
                        <td><?= $row['nama_user'] ?></td>
                        <td><?= $row['aktivitas'] ?></td>
                    </tr>
                    <?php endwhile; ?>
                    <?php if(mysqli_num_rows($result) == 0): ?>
                        <tr><td colspan="3" class="text-center">Belum ada aktivitas tercatat.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../../layouts/footer.php'; ?>
