# Panduan Hosting Aplikasi Parkir

Aplikasi ini telah disiapkan untuk dapat dihosting di berbagai platform. Berikut adalah cara konfigurasinya:

## Persiapan Database
1. Impor file `database.sql` ke MySQL database Anda.
2. Jika hosting menggunakan database eksternal, catat Detail Host, Username, Password, dan Nama Database.

## Konfigurasi Environment Variables
Aplikasi menggunakan variabel lingkungan untuk koneksi database. Atur variabel berikut di panel hosting Anda:

- `DB_HOST`: Host database (contoh: `localhost` atau IP hosting)
- `DB_USER`: Username database
- `DB_PASS`: Password database
- `DB_NAME`: Nama database (`ukk_parkir`)
- `BASE_URL`: URL lengkap aplikasi (contoh: `https://aplikasi-parkir.vercel.app/`)

---

## Opsi 1: Hosting di Vercel (Gratis)
Aplikasi ini sudah mendukung Vercel menggunakan `vercel-php`.
1. Hubungkan atau unggah repositori ke Vercel.
2. Tambahkan **Environment Variables** di dashboard Vercel sesuai daftar di atas.
3. Vercel akan membaca `vercel.json` dan melakukan deploy secara otomatis.
   *Catatan: Anda membutuhkan database MySQL eksternal (misal: PlanetScale, TiDB, atau Aiven) karena Vercel tidak menyediakan MySQL.*

## Opsi 2: Hosting Menggunakan Docker (Railway/Render)
Gunakan `Dockerfile` yang telah disediakan.
1. Hubungkan repositori ke Railway atau Render.
2. Platform tersebut akan otomatis mendeteksi `Dockerfile`.
3. Tambahkan environment variables di dashboard layanan tersebut.

## Opsi 3: Hosting di Shared Hosting (cPanel)
1. Unggah semua file ke folder `public_html`.
2. Pastikan file `.htaccess` atau konfigurasi server Apache mengizinkan akses.
3. Edit `config/database.php` jika ingin hardcode (namun disarankan tetap menggunakan Environment Variables jika hosting mendukung).

---
Dibuat untuk persiapan UKK.
