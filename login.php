<?php
session_start();
require 'db.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $query = $koneksi->query("SELECT * FROM users WHERE username='$username' AND password='$password'");
    if ($query->num_rows > 0) {
        $_SESSION['login'] = true;
        header("Location: kasir.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<link rel="stylesheet" href="style.css">

<div class="login-container">
    <form method="POST" class="login-box">
        <h2>Aplikasi Kasir</h2>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Masuk</button>
    </form>
</div>
