<?php
// Register (sengaja vuln SQLi & stored XSS lewat username jika ditampilkan di tempat lain)
require_once __DIR__ . '/../config/config.php';

$info = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    // SQLi: insert tanpa sanitasi
    $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
    if (@mysqli_query($conn, $sql)) {
        $info = "Register sukses untuk: $username"; // Reflected XSS
    } else {
        $info = "Register gagal untuk: $username";
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Register (Vuln)</title>
  <style>
    body { font-family: sans-serif; margin: 20px; }
    .nav { margin-bottom: 16px; }
    input { width: 100%; padding: 8px; margin: 4px 0; }
  </style>
</head>
<body>
  <div class="nav">
    <a href="login.php">Login</a> |
    <a href="register.php">Register</a> |
    <a href="dashboard.php">Dashboard</a> |
    <a href="logout.php">Logout</a>
  </div>

  <h1>Register Pengguna</h1>
  <?php if ($info): ?>
    <!-- Reflected XSS -->
    <p><?php echo $info ?></p>
  <?php endif; ?>
  <form method="post" action="">
    <label>Username</label>
    <input type="text" name="username" placeholder="bisa XSS: <script>alert('XSS')</script>">
    <label>Password</label>
    <input type="password" name="password" placeholder="coba SQLi di username">
    <button type="submit">Daftar</button>
  </form>

  <p><small>Aplikasi ini sengaja tidak aman untuk latihan SQLi/XSS. Jangan pakai di produksi.</small></p>
</body>
</html>