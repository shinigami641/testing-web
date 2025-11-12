--
-- SQL init untuk aplikasi vuln (SQLi & XSS)
-- PERINGATAN: Data dan schema ini sengaja tidak aman. Gunakan hanya untuk pembelajaran/pentest lokal.
--

CREATE DATABASE IF NOT EXISTS `testing_web`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE `testing_web`;

-- Hapus tabel jika ada (opsional)
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `products`;

-- Tabel users (password plaintext, SENGAJA tidak aman)
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(255),
  `password` VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel products (menyimpan konten tanpa sanitasi)
CREATE TABLE `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` TEXT,
  `description` TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed data users (plaintext, contoh untuk uji SQLi/XSS)
INSERT INTO `users` (`username`, `password`) VALUES
  ('admin', 'admin123'),
  ('alice', 'alicepass'),
  ('bob', 'bobpass'),
  ('xssuser', '<script>alert("XSS in username")</script>');

-- Seed data products (stored XSS)
INSERT INTO `products` (`name`, `description`) VALUES
  ('Produk Demo', 'Deskripsi produk demo. Bisa disisipi <script>alert(\'XSS\')</script> untuk uji XSS'),
  ('Laptop', 'Laptop murah & cepat'),
  ('Coffee', 'Kopi enak & wangi'),
  ('XSS Product', '<img src=x onerror=alert("XSS image")>');

-- Selesai