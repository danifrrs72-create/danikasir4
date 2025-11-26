<?php
require 'db.php';
$id = $_GET['id'];

$q = $koneksi->query("
SELECT produk.nama, detail_penjualan.qty, detail_penjualan.harga
FROM detail_penjualan
JOIN produk ON produk.id = detail_penjualan.produk_id
WHERE detail_penjualan.penjualan_id = $id
");

echo "<h4>Detail Transaksi ID: $id</h4>";
echo "<table class='table table-bordered mt-2'>";
echo "<tr><th>Produk</th><th>Qty</th><th>Harga</th><th>Subtotal</th></tr>";

while($d = $q->fetch_assoc()) {
    $sub = $d['qty'] * $d['harga'];
    echo "<tr>
        <td>{$d['nama']}</td>
        <td>{$d['qty']}</td>
        <td>Rp ".number_format($d['harga'],0,',','.')."</td>
        <td>Rp ".number_format($sub,0,',','.')."</td>
    </tr>";
}
echo "</table>";

echo "<a href='riwayat.php' class='btn btn-secondary mt-3'>Kembali</a>";
