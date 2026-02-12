<?php
session_start();

// Base URL definition
// Adjust this if your project folder name is different
define('BASE_URL', 'http://localhost/nentetnoviani/');

function base_url($path = '') {
    return BASE_URL . $path;
}

function redirect($path) {
    header("Location: " . base_url($path));
    exit;
}

function set_flash_message($type, $message) {
    $_SESSION['flash_message'] = [
        'type' => $type, // success, danger, warning, info
        'message' => $message
    ];
}

function get_flash_message() {
    if (isset($_SESSION['flash_message'])) {
        $flash = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return '<div class="alert alert-' . $flash['type'] . ' alert-dismissible fade show" role="alert">
                    ' . $flash['message'] . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
    }
    return '';
}

function check_login() {
    if (!isset($_SESSION['user'])) {
        redirect('login.php');
    }
}

function check_role($allowed_roles) {
    if (!in_array($_SESSION['user']['role'], $allowed_roles)) {
        echo "<script>alert('Anda tidak memiliki akses ke halaman ini!'); window.location.href='" . base_url() . "';</script>";
        exit;
    }
}
?>
