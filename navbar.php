<div class="navbar">
    <div class="nav-brand">Aplikasi Kasir</div>
    <div class="nav-menu">
        <a href="kasir.php" class="<?= basename($_SERVER['PHP_SELF']) == 'kasir.php' ? 'active' : '' ?>">Kasir</a>
        <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">Manajemen Stok</a>
        <a href="pelanggan.php" class="<?= basename($_SERVER['PHP_SELF']) == 'pelanggan.php' ? 'active' : '' ?>">Pelanggan</a>
        <a href="riwayat.php" class="<?= basename($_SERVER['PHP_SELF']) == 'riwayat.php' ? 'active' : '' ?>">Riwayat Transaksi</a>
        <a href="logout.php" style="float:right;">Logout</a>

    </div>
</div>
