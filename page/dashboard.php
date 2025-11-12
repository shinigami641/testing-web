<?php
// Dashboard sederhana dengan session (sengaja vuln XSS pada output)
require_once __DIR__ . '/../config/config.php';
session_start();

// Proteksi sangat minimal: hanya cek jika sudah login
$isLoggedIn = isset($_SESSION['user_id']);

// Simpan catatan ke session (stored XSS melalui session)
if ($isLoggedIn && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['note'])) {
    // Tidak ada sanitasi
    $_SESSION['note'] = $_POST['note'];
}

$username = $isLoggedIn ? ($_SESSION['username'] ?? 'pengguna') : '';
$note = $_SESSION['note'] ?? '';
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Dashboard</title>
  <style>
    body { font-family: sans-serif; margin: 20px; }
    .nav { margin-bottom: 16px; }
    input, textarea { width: 100%; padding: 8px; margin: 4px 0; }
    .danger { color: #c00; }
    .card { border: 1px solid #ccc; padding: 12px; }
  </style>
</head>
<body>
  <div class="nav">
    <a href="login.php">Login</a> |
    <a href="register.php">Register</a> |
    <a href="dashboard.php">Dashboard</a> |
    <a href="logout.php">Logout</a>
  </div>

  <h1>Dashboard</h1>
  <?php if (!$isLoggedIn): ?>
    <p class="danger">Anda belum login. Silakan <a href="login.php">login</a> terlebih dahulu.</p>
  <?php else: ?>
    <!-- Reflected XSS: menampilkan username tanpa sanitasi -->
    <p>Selamat datang, <strong class="danger"><?php echo $username ?></strong></p>

    <div class="card">
      <h3>Catatan Anda (Stored XSS via session)</h3>
      <?php if ($note !== ''): ?>
        <p><?php echo nl2br($note) ?></p>
      <?php else: ?>
        <p>Belum ada catatan.</p>
      <?php endif; ?>
      <form method="post" action="">
        <label>Tulis catatan (contoh payload: <script>alert('XSS')</script>)</label>
        <textarea name="note" rows="3"></textarea>
        <button type="submit">Simpan Catatan</button>
      </form>
    </div>
  <?php endif; ?>

  <p><small>Aplikasi ini sengaja tidak aman (XSS). Gunakan hanya untuk pembelajaran/pentest lokal.</small></p>
</body>
</html>