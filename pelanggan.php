<?php

session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}


require 'db.php';
include 'navbar.php';

// Tambah pelanggan
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $telepon = $_POST['telepon'];
    $koneksi->query("INSERT INTO pelanggan (nama, alamat, telepon) VALUES ('$nama', '$alamat', '$telepon')");
    header("Location: pelanggan.php");
    exit;
}

// Hapus pelanggan
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $koneksi->query("DELETE FROM pelanggan WHERE id=$id");
    header("Location: pelanggan.php");
    exit;
}

$pelanggan = $koneksi->query("SELECT * FROM pelanggan ORDER BY id DESC");
?>
<link rel="stylesheet" href="style.css">


<div class="container">
    <h2 class="page-title">Manajemen Pelanggan</h2>

    <!-- Form Tambah -->
    <form method="POST" class="form-box">
        <input type="text" name="nama" placeholder="Nama Pelanggan" required>
        <input type="text" name="alamat" placeholder="Alamat" required>
        <input type="text" name="telepon" placeholder="Nomor Telepon" required>
        <button type="submit" name="tambah" class="btn-add">Tambah Pelanggan</button>
    </form>

    <!-- Search -->
    <input type="text" id="search" class="search-input" placeholder="Cari pelanggan...">

    <!-- Tabel -->
    <table class="table">
        <thead>
            <tr>
                <th>Nama Pelanggan</th>
                <th>Alamat</th>
                <th>Telepon</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="data">
            <?php while ($row = $pelanggan->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['nama'] ?></td>
                <td><?= $row['alamat'] ?></td>
                <td><?= $row['telepon'] ?></td>
                <td><a href="?hapus=<?= $row['id'] ?>" class="btn-delete" onclick="return confirm('Hapus pelanggan?')">Hapus</a></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script>
// Fitur pencarian langsung
document.getElementById("search").addEventListener("keyup", function(){
    let filter = this.value.toLowerCase();
    document.querySelectorAll("#data tr").forEach(tr => {
        tr.style.display = tr.textContent.toLowerCase().includes(filter) ? "" : "none";
    });
});
</script>
