<?php
require_once 'functions/helpers.php';

// Check login first
check_login();

// Redirect based on role
$role = $_SESSION['user']['role'];

switch ($role) {
    case 'admin':
        header("Location: " . base_url('pages/admin/dashboard.php'));
        break;
    case 'petugas':
        header("Location: " . base_url('pages/petugas/dashboard.php'));
        break;
    case 'owner':
        header("Location: " . base_url('pages/owner/dashboard.php'));
        break;
    default:
        // Should not happen if roles are well defined
        session_destroy();
        redirect('login.php');
        break;
}
exit;
?>
