# Mini PHP Vulnerable App (SQLi & XSS)

PERINGATAN: Aplikasi ini sengaja TIDAK AMAN. Gunakan hanya untuk tujuan pembelajaran/pengujian di lingkungan lokal. Jangan expose ke internet/jaringan publik.

Fitur:
- Login (SQLi, reflected XSS)
- Register (SQLi, reflected XSS)
- Dashboard (session-based, stored & reflected XSS via session)
- Logout (hapus session)

Koneksi DB (sesuaikan dengan koneksi anda):
- Host: localhost
- Port: 3306
- User: root
- Password: 12345
- Database: testing_web

Catatan koneksi dari dalam Docker:
- Di macOS/Windows, container tidak dapat mengakses "localhost" host secara langsung. Gunakan `host.docker.internal` sebagai host DB. Kode ini otomatis mencoba `localhost` dulu, lalu fallback ke `host.docker.internal`.

Cara jalankan:
1. Pastikan MySQL lokal berjalan dan kredensial sesuai di atas. Buat user root dengan password 12345 jika perlu.
2. Build image:
   docker build -t vuln-php-web .
3. Run container:
   docker run --rm -p 8080:80 vuln-php-web
4. Jalankan dengan Docker Compose:
   - docker compose build
   - docker compose up -d
   - Akses: http://localhost:8081 (atau port yang Anda gunakan)

Eksploit contoh:
- SQLi login: username: ' OR '1'='1  password: apapun
- Reflected XSS pada pesan login/register.
- Stored XSS di dashboard: masukkan `<script>alert('XSS')</script>` dalam catatan, akan disimpan di session dan ditampilkan kembali.

Struktur proyek:
- index.php (di luar folder, halaman utama produk)
- page/ (folder yang berisi halaman)
  - login.php, register.php, dashboard.php, logout.php
- config/
  - config.php (konfigurasi & koneksi DB)

Docker Compose:
- File docker-compose.yml setup service web (php:8.2-apache) dan memetakan port 8080.
- Variabel environment DB_* bisa di-override via .env atau perintah compose (default ke host.docker.internal:3306, root/12345, testing_web).
- extra_hosts: host.docker.internal:host-gateway disediakan agar container bisa resolve host machine.

JANGAN gunakan kode ini untuk produksi.
