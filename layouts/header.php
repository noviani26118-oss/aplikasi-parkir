<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Parkir UKK</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --cute-blue: #A7C7E7;
            --soft-blue: #E1F0FF;
            --deep-cute-blue: #7FB3D5;
            --text-color: #4A4A4A;
        }
        body { 
            background-color: var(--soft-blue); 
            font-family: 'Poppins', sans-serif;
            color: var(--text-color);
        }
        .sidebar { 
            min-height: 100vh; 
            background-color: var(--cute-blue); 
            color: white; 
            box-shadow: 2px 0 5px rgba(0,0,0,0.05);
        }
        .sidebar a { 
            color: #5D6D7E; 
            text-decoration: none; 
            display: block; 
            padding: 12px 20px; 
            margin: 4px 10px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .sidebar a:hover, .sidebar a.active { 
            background-color: white; 
            color: var(--deep-cute-blue); 
            transform: translateX(5px);
        }
        .sidebar-heading {
            color: #7B8A97 !important;
            font-weight: 600;
            padding: 0 25px;
        }
        .content { padding: 20px; }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .btn-primary {
            background-color: var(--deep-cute-blue);
            border: none;
            border-radius: 10px;
            padding: 8px 20px;
        }
        .btn-primary:hover {
            background-color: #6DA3C5;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-0 d-md-none">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">E-Parking</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mobileMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mobileMenu">
      <!-- Mobile menu items will be same as sidebar but strictly for mobile -->
       <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="<?= base_url('logout.php') ?>">Logout</a></li>
       </ul>
    </div>
  </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar (Desktop) -->
        <?php include 'sidebar.php'; ?>
        
        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <?= get_flash_message() ?>
