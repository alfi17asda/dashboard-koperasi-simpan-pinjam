# Dashboard Koperasi (PHP + MySQL + PhpMyAdmin)

Struktur cepat:
- `public/` : halaman utama (dashboard & UI)
- `app/` : backend (konfigurasi database, koneksi, helper)
- `src/` : fitur (CRUD anggota, simpanan, pinjaman, kasir, akunting, laporan, transaksi)
- `sql/` : script database untuk PhpMyAdmin

## Cara menjalankan
1. Buat database MySQL/PhpMyAdmin, lalu import:
   - `sql/koperasi.sql`
2. Update koneksi database di:
   - `app/config.php`
3. Jalankan melalui server lokal PHP, contoh:
   - `php -S localhost:8000 -t public`
4. Buka:
   - `http://localhost:8000`

## Catatan
- Ini template dasar yang siap dikembangkan: dashboard + menu fitur + database schema.

