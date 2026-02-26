<?php
session_start();

// Base URL definition
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$base_path = (strpos($host, 'localhost') !== false) ? '/nentetnoviani/' : '/';
define('BASE_URL', $protocol . $host . $base_path);


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
function log_activity($activity) {
    global $conn;
    if (isset($_SESSION['user'])) {
        $id_user = $_SESSION['user']['id_user'];
        $activity = mysqli_real_escape_string($conn, $activity);
        $query = "INSERT INTO tabel_log_aktivitas (id_user, aktivitas) VALUES ('$id_user', '$activity')";
        mysqli_query($conn, $query);
    }
}
?>
