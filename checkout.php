<?php
require 'db.php';
session_start();

$cart = $_SESSION['cart'] ?? [];
if (!$cart) die("<script>alert('Keranjang kosong');window.location='kasir.php';</script>");

$total = 0;
$ids = implode(',', array_keys($cart));
$res = $koneksi->query("SELECT * FROM produk WHERE id IN ($ids)");
while($r = $res->fetch_assoc()) {
    $total += $cart[$r['id']] * $r['harga'];
}

// ambil pembayaran hanya dari POST (bukan GET)
$bayar = isset($_POST['bayar']) ? (int)$_POST['bayar'] : 0;

// jika pembayaran belum diinput, balik ke cart
if ($bayar <= 0) {
    echo "<script>alert('Masukkan nominal pembayaran dulu');window.location='cart.php';</script>";
    exit;
}

// cek uang kurang
if ($bayar < $total) {
    echo "<script>alert('Pembayaran kurang');window.location='cart.php';</script>";
    exit;
}

$kembali = $bayar - $total;
// ambil daftar barang untuk ditampilkan
$items = [];
$res = $koneksi->query("SELECT * FROM produk WHERE id IN ($ids)");
while($r = $res->fetch_assoc()){
    $r['qty'] = $cart[$r['id']];
    $r['sub'] = $r['qty'] * $r['harga'];
    $items[] = $r;
}
// simpan ke tabel penjualan
$koneksi->query("INSERT INTO penjualan SET 
    total = $total,
    bayar = $bayar,
    kembalian = $kembali,
    tanggal = NOW()
");
$pid = $koneksi->insert_id;

// simpan ke tabel detail penjualan & kurangi stok
foreach ($items as $it) {
    $koneksi->query("INSERT INTO detail_penjualan SET 
        penjualan_id = $pid,
        produk_id = {$it['id']},
        qty = {$it['qty']},
        harga = {$it['harga']}
    ");
    $koneksi->query("UPDATE produk SET stok = stok - {$it['qty']} WHERE id = {$it['id']}");
}

// kosongkan keranjang setelah checkout
unset($_SESSION['cart']);

?>
<!DOCTYPE html>
<html>
<head>
<title>Struk Pembayaran</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">

<style>
@media print {
    nav, .btn, .btn-primary, .btn-success, .btn-warning, .btn-secondary { display:none !important; }
    body { background:#fff !important; }
    .card { box-shadow:none !important; border:none !important; }
}
</style>
</head>
<body class="p-4">

<?php include "navbar.php"; ?>

<div class="card p-4" style="max-width:650px; margin:auto;">
    <h2 class="text-center mb-3">🧾 Struk Transaksi</h2>

    <p><b>Tanggal:</b> <?=date("d-m-Y H:i:s")?></p>
    <p><b>ID Transaksi:</b> <?=$pid?></p>

    <table class="table mt-3">
        <tr><th>Produk</th><th>Qty</th><th>Harga</th><th>Subtotal</th></tr>
        <?php foreach($items as $it): ?>
        <tr>
            <td><?=$it['nama']?></td>
            <td><?=$it['qty']?></td>
            <td>Rp <?=number_format($it['harga'],2,',','.')?></td>
            <td>Rp <?=number_format($it['sub'],2,',','.')?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <hr>
    <h4><b>Total: Rp <?=number_format($total,2,',','.')?></b></h4>
    <h4><b>Tunai: Rp <?=number_format($bayar,2,',','.')?></b></h4>
    <h4><b>Kembalian: Rp <?=number_format($kembali,2,',','.')?></b></h4>

    <div class="text-center mt-4">
        <button onclick="window.print()" class="btn btn-warning">🖨 Cetak Struk</button>
    </div>

    <div class="text-center mt-3">
        <div style="text-align:center; margin-top:25px;">
    <button onclick="window.print()" class="btn btn-warning">🖨 Cetak Struk</button>
</div>

        <a href="kasir.php" class="btn btn-primary">Kembali ke Kasir</a>
        <a href="riwayat.php" class="btn btn-secondary">Riwayat Transaksi</a>
    </div>
</div>

</body>
</html>