<?php
// Logout: hancurkan session
session_start();
session_unset();
session_destroy();
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Logout</title>
  <style>
    body { font-family: sans-serif; margin: 20px; }
    .nav { margin-bottom: 16px; }
  </style>
</head>
<body>
  <div class="nav">
    <a href="login.php">Login</a> |
    <a href="register.php">Register</a> |
    <a href="dashboard.php">Dashboard</a>
  </div>
  <h1>Anda sudah logout</h1>
  <p>Session telah dihapus. Silakan <a href="login.php">login</a> kembali.</p>
</body>
</html>