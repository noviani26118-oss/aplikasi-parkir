<?php
require_once 'functions/helpers.php';
require_once 'config/database.php';

if (isset($_SESSION['user'])) {
    log_activity("User " . $_SESSION['user']['nama_user'] . " logout.");
}

session_destroy();
redirect('login.php');
?>
