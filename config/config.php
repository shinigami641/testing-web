<?php
// Konfigurasi & Koneksi DB untuk aplikasi (sengaja tidak aman, untuk latihan)
// Menggunakan fallback host.docker.internal agar container bisa akses MySQL host.

// Nonaktifkan exception bawaan mysqli supaya gagal koneksi tidak fatal
if (function_exists('mysqli_report')) {
    mysqli_report(MYSQLI_REPORT_OFF);
}

$DB_HOST = getenv('DB_HOST') ?: 'localhost';
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASS') ?: '12345';
$DB_NAME = getenv('DB_NAME') ?: 'testing_web';
$DB_PORT = getenv('DB_PORT') ?: 3306;

function try_connect($host, $user, $pass, $db = null, $port = 3306) {
    try {
        $conn = mysqli_connect($host, $user, $pass, $db, $port);
        return $conn ?: null;
    } catch (Throwable $e) {
        return null;
    }
}

// Coba konek ke host dari env, jika gagal fallback ke host.docker.internal
$__conn = try_connect($DB_HOST, $DB_USER, $DB_PASS, null, $DB_PORT);
if (!$__conn) {
    $__conn = try_connect('host.docker.internal', $DB_USER, $DB_PASS, null, $DB_PORT);
    if ($__conn) {
        $DB_HOST = 'host.docker.internal';
    }
}
if (!$__conn) {
    die('Gagal konek ke MySQL server. Pastikan MySQL berjalan di localhost:3306 (root/12345) atau gunakan host.docker.internal dari dalam container.');
}

// Buat database jika belum ada
@mysqli_query($__conn, "CREATE DATABASE IF NOT EXISTS `$DB_NAME` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");

// Konek ke database spesifik
$conn = try_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);
if (!$conn) {
    die('Gagal konek ke database '.$DB_NAME.' di host '.$DB_HOST.' (root/12345).');
}

// Tabel users
$createUsers = "CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(255),
  password VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
@mysqli_query($conn, $createUsers);

// Tabel products (dipakai oleh index.php jika ada)
$createProducts = "CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name TEXT,
  description TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
@mysqli_query($conn, $createProducts);

// Seed awal
$checkSeed = @mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM products");
if ($checkSeed) {
    $row = mysqli_fetch_assoc($checkSeed);
    if ((int)$row['cnt'] === 0) {
        @mysqli_query($conn, "INSERT INTO products (name, description) VALUES 
            ('Produk Demo', 'Deskripsi produk demo. Bisa disisipi <script>alert(\'XSS\')</script>'),
            ('Laptop', 'Laptop murah & cepat'),
            ('Coffee', 'Kopi enak & wangi')
        ");
    }
}

// Catatan: Jangan gunakan ini untuk produksi.
?>