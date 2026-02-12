<?php
require_once '../../config/database.php';
require_once '../../functions/helpers.php';
check_login();

$id = $_GET['id'] ?? 0;
// Fetch details
$query = "SELECT t.*, k.nama_kendaraan, u.nama_user as petugas 
          FROM tabel_transaksi t 
          JOIN tabel_kendaraan k ON t.id_kendaraan = k.id_kendaraan
          JOIN tabel_users u ON t.id_user = u.id_user
          WHERE t.id_transaksi = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Data tidak ditemukan");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Struk Parkir - <?= $data['kode_transaksi'] ?></title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; width: 300px; margin: 20px auto; text-align: center; }
        .header { margin-bottom: 20px; border-bottom: 1px dashed black; padding-bottom: 10px; }
        .details { text-align: left; margin-bottom: 20px; }
        .total { border-top: 1px dashed black; border-bottom: 1px dashed black; padding: 10px 0; font-size: 1.2em; font-weight: bold; }
        .footer { margin-top: 20px; font-size: 0.8em; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">

<div class="header">
    <h3>E-PARKING JAYA</h3>
    <p>Jl. Raya UKK No. 1</p>
</div>

<div class="details">
    <table>
        <tr><td>Kode TRX</td>   <td>: <?= $data['kode_transaksi'] ?></td></tr>
        <tr><td>Plat Nomor</td> <td>: <?= $data['plat_nomor'] ?></td></tr>
        <tr><td>Kendaraan</td>  <td>: <?= $data['nama_kendaraan'] ?></td></tr>
        <tr><td>Masuk</td>      <td>: <?= $data['jam_masuk'] ?></td></tr>
        <tr><td>Keluar</td>     <td>: <?= $data['jam_keluar'] ?></td></tr>
        <tr><td>Lama</td>       <td>: <?= $data['lama_parkir'] ?> Jam</td></tr>
        <tr><td>Petugas</td>    <td>: <?= $data['petugas'] ?></td></tr>
    </table>
</div>

<div class="total">
    TOTAL: Rp <?= number_format($data['total_bayar'], 0, ',', '.') ?>
</div>

<div class="footer">
    <p>Terima Kasih Atas Kunjungan Anda</p>
    <p><?= date("d-m-Y H:i:s") ?></p>
</div>

<button class="no-print" onclick="window.location.href='parkir_keluar.php'">Kembali</button>

</body>
</html>
