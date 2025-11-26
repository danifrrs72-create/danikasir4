<?php

session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}


require 'db.php';
$msg = '';

if (isset($_POST['add'])) {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $koneksi->query("INSERT INTO produk (nama,harga,stok) VALUES ('$nama',$harga,$stok)");
    $msg = "Produk ditambahkan.";
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $koneksi->query("DELETE FROM produk WHERE id=$id");
    header("Location: index.php");
}

$produk = $koneksi->query("SELECT * FROM produk ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
<title>Manajemen Produk</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- letakkan di <head> -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">

</head>
<body class="p-4">
   <?php include "navbar.php"; ?>
 
<h3>Manajemen Produk</h3>
<?php if($msg): ?><div class="alert alert-success"><?=$msg?></div><?php endif; ?>
<form method="post" class="row g-2 mb-3">
  <div class="col"><input name="nama" class="form-control" placeholder="Nama produk" required></div>
  <div class="col"><input name="harga" type="number" step="0.01" class="form-control" placeholder="Harga" required></div>
  <div class="col"><input name="stok" type="number" class="form-control" placeholder="Stok" required></div>
  <div class="col"><button name="add" class="btn btn-primary">Tambah</button>
       <a href="kasir.php" class="btn btn-success">Buka Kasir</a></div>
       <a href="riwayat.php" class="btn btn-primary">Riwayat Transaksi</a>

</form>

<table class="table table-striped">
<thead>
<tr><th>#</th><th>Nama</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr>
</thead>
<tbody>
<?php while($r = $produk->fetch_assoc()): ?>
<tr>
  <td><?=$r['id']?></td>
  <td><?=$r['nama']?></td>
  <td>Rp <?=number_format($r['harga'],2,',','.')?></td>
  <td><?=$r['stok']?></td>
  <td><a href="?delete=<?=$r['id']?>" onclick="return confirm('Hapus?')" class="btn btn-sm btn-danger">Hapus</a></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</body>
</html>
