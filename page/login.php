<?php
// Login (sengaja vuln SQLi & reflected XSS)
require_once __DIR__ . '/../config/config.php';
session_start();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // SQLi: Query tanpa prepared statements
    $sql = "SELECT id, username FROM users WHERE username='$username' AND password='$password' LIMIT 1";
    $res = @mysqli_query($conn, $sql);
    if ($res && mysqli_num_rows($res) === 1) {
        $row = mysqli_fetch_assoc($res);
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        // Reflected XSS: pesan menampilkan input mentah
        $message = "Selamat datang, $username";
    } else {
        $message = "Login gagal untuk pengguna: $username";
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Login (Vuln)</title>
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

  <h1>Login</h1>
  <?php if ($message): ?>
    <!-- Reflected XSS di sini -->
    <p><?php echo $message ?></p>
  <?php endif; ?>
  <form method="post" action="">
    <label>Username</label>
    <input type="text" name="username" placeholder="coba SQLi: ' OR '1'='1">
    <label>Password</label>
    <input type="password" name="password" placeholder="coba payload XSS di username">
    <button type="submit">Masuk</button>
  </form>

  <p><small>Aplikasi ini sengaja tidak aman untuk latihan SQLi/XSS. Jangan pakai di produksi.</small></p>
</body>
</html>