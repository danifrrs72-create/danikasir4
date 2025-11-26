<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

require 'db.php';

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

if (isset($_GET['add'])) {
    $id = $_GET['add'];
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
    header("Location: kasir.php");
    exit;
}

$produk = $koneksi->query("SELECT * FROM produk");
?>

<!DOCTYPE html>
<html>
<head>
<title>Kasir</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- letakkan di <head> -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">

</head>
<body class="p-4">
    <?php include "navbar.php"; ?>

<a href="index.php" class="btn btn-secondary mb-3">Manajemen Produk</a>
<a href="cart.php" class="btn btn-primary mb-3">Keranjang (<?=count($_SESSION['cart'])?>)</a>
<div class="row">
<?php while($r = $produk->fetch_assoc()): ?>
  <div class="col-md-4 mb-3">
  <div class="product-card">
      <div class="name"><?=$r['nama']?></div>
      <div class="meta">Harga: Rp <?=number_format($r['harga'],2,',','.')?></div>
      <div class="meta">Stok: <?=$r['stok']?></div>

      <?php if($r['stok'] > 0): ?>
        <a href="?add=<?=$r['id']?>" class="btn btn-success" style="margin-top:10px;">Tambah ke Keranjang</a>
      <?php else: ?>
        <button class="btn btn-danger" disabled style="margin-top:10px;">Stok Habis</button>
      <?php endif; ?>
  </div>
</div>

<?php endwhile; ?>
</div>
</body>
</html>
