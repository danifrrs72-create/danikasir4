<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'db.php';
include "navbar.php";
$riwayat = $koneksi->query("
    SELECT penjualan.id, penjualan.tanggal, penjualan.total, penjualan.bayar, penjualan.kembalian
    FROM penjualan ORDER BY penjualan.id DESC
");
?>
<!DOCTYPE html>
<html>
<head>
<title>Riwayat Transaksi</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
  <h2>Riwayat Transaksi</h2>
  <div class="card">
    <table class="table">
      <thead>
        <tr><th>ID</th><th>Tanggal</th><th>Total</th><th>Bayar</th><th>Kembalian</th><th>Aksi</th></tr>
      </thead>
      <tbody>
      <?php while($r = $riwayat->fetch_assoc()): ?>
        <tr>
          <td><?=$r['id']?></td>
          <td><?=$r['tanggal']?></td>
          <td>Rp <?=number_format($r['total'],2,',','.')?></td>
          <td>Rp <?=number_format($r['bayar'],2,',','.')?></td>
          <td>Rp <?=number_format($r['kembalian'],2,',','.')?></td>
          <td><a href="detail.php?id=<?=$r['id']?>" class="btn btn-primary">Detail</a></td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
