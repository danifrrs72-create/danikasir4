<?php
require 'db.php';
session_start();
$cart = $_SESSION['cart'] ?? [];

if (isset($_POST['update'])) {
    foreach($_POST['qty'] as $id => $q) {
        $q = (int)$q;
        if ($q <= 0) unset($cart[$id]); else $cart[$id] = $q;
    }
    $_SESSION['cart'] = $cart;
    header("Location: cart.php");
}

$items = [];
$total = 0;
if ($cart) {
    $ids = implode(',', array_keys($cart));
    $res = $koneksi->query("SELECT * FROM produk WHERE id IN ($ids)");
    while($r = $res->fetch_assoc()){
        $r['qty'] = $cart[$r['id']];
        $r['sub'] = $r['qty'] * $r['harga'];
        $items[] = $r;
        $total += $r['sub'];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Keranjang</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- letakkan di <head> -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">

</head>
<body class="p-4">
    <?php include "navbar.php"; ?>

<a href="kasir.php" class="btn btn-secondary mb-3">Kembali</a>
<form method="post">
<table class="table">
<tr><th>Produk</th><th>Harga</th><th>Qty</th><th>Subtotal</th></tr>
<?php foreach($items as $it): ?>
<tr>
<td><?=$it['nama']?></td>
<td>Rp <?=number_format($it['harga'],2,',','.')?></td>
<td><input type="number" name="qty[<?=$it['id']?>]" value="<?=$it['qty']?>" min="0" class="form-control" style="width:90px"></td>
<td>Rp <?=number_format($it['sub'],2,',','.')?></td>
</tr>
<?php endforeach; ?>
</table>
<button name="update" class="btn btn-primary">Update</button>
<a href="checkout.php" class="btn btn-success">Checkout</a>
<?php if ($total > 0): ?>
    <hr>
    <h4><b>Total: Rp <?=number_format($total,2,',','.')?></b></h4>

    <div class="mt-3">
        <label style="font-size:18px; font-weight:700;">Uang Pembayaran (Tunai)</label>
        <input type="number" name="bayar" class="form-control" placeholder="Masukkan nominal uang" required style="max-width: 250px; margin-top:6px;">
    </div>

    <button type="submit" formaction="checkout.php" class="btn btn-success mt-3">Checkout</button>
<?php endif; ?>

</form>
<hr>
<h4>Total: Rp <?=number_format($total,2,',','.')?></h4>
</body>
</html>
