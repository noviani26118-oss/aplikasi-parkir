<?php
$role = $_SESSION['user']['role'] ?? '';
?>
<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
    <div class="position-sticky pt-3">
        <h4 class="text-center py-3 border-bottom" style="font-weight: 600; color: #5D6D7E;">E-Parking</h4>
        <div class="text-center my-3">
            <div class="bg-white d-inline-block rounded-circle p-3 shadow-sm" style="width: 100px; height: 100px; line-height: 70px;">
                <i class="fas fa-parking fa-4x" style="color: var(--cute-blue);"></i>
            </div>
        </div>
        <ul class="nav flex-column">
            
            <!-- Universal Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url() ?>">
                    <i class="fas fa-home me-2"></i> Dashboard
                </a>
            </li>

            <?php if ($role == 'admin'): ?>
            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                <span>Admin Menu</span>
            </h6>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('pages/admin/users.php') ?>">
                    <i class="fas fa-users me-2"></i> Kelola User
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('pages/admin/kendaraan.php') ?>">
                    <i class="fas fa-car me-2"></i> Kelola Kendaraan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('pages/admin/area.php') ?>">
                    <i class="fas fa-map-marker-alt me-2"></i> Area Parkir
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('pages/admin/tarif.php') ?>">
                    <i class="fas fa-money-bill me-2"></i> Tarif Parkir
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('pages/admin/logs.php') ?>">
                    <i class="fas fa-history me-2"></i> Log Aktivitas
                </a>
            </li>
            <?php endif; ?>

            <?php if ($role == 'petugas'): ?>
            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                <span>Transaksi</span>
            </h6>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('pages/petugas/parkir_masuk.php') ?>">
                    <i class="fas fa-sign-in-alt me-2"></i> Parkir Masuk
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('pages/petugas/parkir_keluar.php') ?>">
                    <i class="fas fa-sign-out-alt me-2"></i> Parkir Keluar
                </a>
            </li>
            <?php endif; ?>

            <?php if ($role == 'owner'): ?>
            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                <span>Laporan</span>
            </h6>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('pages/owner/laporan.php') ?>">
                    <i class="fas fa-file-invoice-dollar me-2"></i> Laporan Pendapatan
                </a>
            </li>
            <?php endif; ?>

            <hr>
            <li class="nav-item">
                <a class="nav-link text-danger" href="<?= base_url('logout.php') ?>">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</nav>
