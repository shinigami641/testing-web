<?php
// Halaman produk (sengaja vuln SQLi & XSS)
require_once __DIR__ . '/config/config.php';

// PRO TIP: jangan lakukan ini di produksi. Ini contoh UNSAFE.

// Tambah produk (tanpa sanitasi)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    $name = $_POST['name'];
    $desc = $_POST['description'] ?? '';
    // SQLi: string dimasukkan langsung tanpa escaping
    $sql = "INSERT INTO products (name, description) VALUES ('$name', '$desc')";
    @mysqli_query($conn, $sql);
}

$q = isset($_GET['q']) ? $_GET['q'] : '';

// Pencarian tidak aman (LIKE dengan input mentah)
$sqlList = "SELECT id, name, description FROM products";
if ($q !== '') {
    $sqlList .= " WHERE name LIKE '%$q%' OR description LIKE '%$q%'";
}
$sqlList .= " ORDER BY id DESC";
$res = @mysqli_query($conn, $sqlList);
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Produk (Vuln SQLi & XSS)</title>
  <style>
    body { font-family: sans-serif; margin: 20px; }
    .nav { margin-bottom: 16px; }
    .card { border: 1px solid #ccc; padding: 12px; margin-bottom: 10px; }
    input, textarea { width: 100%; padding: 8px; margin: 4px 0; }
    .danger { color: #c00; }
  </style>
</head>
<body>
  <div class="nav">
    <a href="index.php">Index Produk</a> |
    <a href="page/login.php">Login</a> |
    <a href="page/register.php">Register</a> |
    <a href="page/dashboard.php">Dashboard</a> |
    <a href="page/logout.php">Logout</a>
  </div>

  <h1>Daftar Produk</h1>
  <form method="get" action="">
    <label>Cari (XSS reflected):</label>
    <input type="text" name="q" value="<?php echo isset($_GET['q']) ? $_GET['q'] : '' ?>">
    <button type="submit">Cari</button>
  </form>
  <?php if ($q !== ''): ?>
    <!-- Reflected XSS: menampilkan kata kunci tanpa sanitasi -->
    <p>Hasil pencarian untuk: <span class="danger"><?php echo $q ?></span></p>
  <?php endif; ?>

  <h2>Tambah Produk Baru (Stored XSS)</h2>
  <form method="post" action="">
    <label>Nama Produk:</label>
    <input type="text" name="name" placeholder="Bisa sisipkan <script>alert('XSS')</script>">
    <label>Deskripsi:</label>
    <textarea name="description" rows="3" placeholder="Ini juga dieksekusi tanpa sanitasi"></textarea>
    <button type="submit">Simpan</button>
  </form>

  <hr>
  <?php if ($res): ?>
    <?php while ($row = mysqli_fetch_assoc($res)): ?>
      <div class="card">
        <!-- Stored XSS: name & description ditampilkan mentah -->
        <h3><?php echo $row['name'] ?></h3>
        <p><?php echo nl2br($row['description']) ?></p>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p>Tidak ada data atau query gagal.</p>
  <?php endif; ?>

  <hr>
  <p><strong>Catatan Keamanan:</strong> Aplikasi ini sengaja TIDAK AMAN untuk latihan exploit SQL Injection & XSS. Jangan gunakan di produksi atau jaringan publik.</p>
</body>
</html>