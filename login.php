<?php
require_once 'config/database.php';
require_once 'functions/helpers.php';

if (isset($_SESSION['user'])) {
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']); 

    $query = "SELECT * FROM tabel_users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user'] = $user;
        
        // Log activity (We will implement log function later, just placeholder logic)
        // mysqli_query($conn, "INSERT INTO tabel_log_aktivitas ...");

        set_flash_message('success', 'Login berhasil! Selamat datang ' . $user['nama_user']);
        redirect('index.php');
    } else {
        set_flash_message('danger', 'Username atau Password salah!');
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aplikasi Parkir</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --cute-blue: #A7C7E7;
            --soft-blue: #E1F0FF;
            --deep-cute-blue: #7FB3D5;
        }
        body {
            background: linear-gradient(135deg, var(--soft-blue) 0%, var(--cute-blue) 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            background: white;
            border: none;
        }
        .form-control {
            border-radius: 10px;
            padding: 12px;
            border: 1px solid #E1E8EE;
        }
        .form-control:focus {
            border-color: var(--cute-blue);
            box-shadow: 0 0 0 0.2rem rgba(167, 199, 231, 0.25);
        }
        .btn-primary {
            background-color: var(--deep-cute-blue);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
        }
        .btn-primary:hover {
            background-color: #6DA3C5;
            transform: translateY(-2px);
            transition: all 0.2s;
        }
        h3 {
            color: #5D6D7E;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="login-card">
    <h3 class="text-center mb-4">Login Parkir</h3>
    
    <?= get_flash_message() ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required autofocus>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
    <div class="mt-3 text-center text-muted">
        <small>UKK RPL - Aplikasi Parkir</small>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
