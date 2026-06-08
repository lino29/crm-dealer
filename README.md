# CRM Dealer

Sistem Customer Relationship Management untuk dealer motor.
Aplikasi ini dikembangkan menggunakan Laravel 11, Blade, Tailwind CSS, Alpine.js, dan MySQL.

## Kebutuhan Sistem

- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL / MariaDB

## Instalasi

1. Clone repository ini:
   ```bash
   git clone <repo-url>
   cd crm-dealer
   ```

2. Install dependensi PHP:
   ```bash
   composer install
   ```

3. Install dependensi JavaScript:
   ```bash
   npm install
   npm run build
   ```

4. Konfigurasi Environment:
   Copy `.env.example` ke `.env` dan sesuaikan koneksi database.
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. Setup Database:
   ```bash
   php artisan migrate --seed
   ```
   (Catatan: pastikan ada `DatabaseSeeder` yang membuat minimal satu user admin dan leader).

## Menjalankan Layanan Background

Untuk memproses notifikasi WhatsApp dan sinkronisasi, Anda perlu menjalankan Queue Worker dan Scheduler:

1. **Menjalankan Queue Worker** (untuk mengirim pesan WA secara asinkron):
   ```bash
   php artisan queue:work
   ```
   *Di production, disarankan menggunakan Supervisor.*

2. **Menjalankan Scheduler** (mengecek jadwal servis harian):
   ```bash
   php artisan schedule:work
   ```
   *Di server production, tambahkan cron job berikut:*
   `* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1`

## Fitur Utama Selesai
1. Autentikasi dan Role Management (Admin & Leader)
2. Manajemen Dealer, Pelanggan, dan Kendaraan
3. Member Card dengan QR Code (html5-qrcode, simple-qrcode, dompdf)
4. Pencatatan Riwayat Servis
5. Penjadwalan Servis & Integrasi Queue untuk Notifikasi WA
6. Laporan (Excel & PDF)
7. Log Aktivitas Scan
8. Pengaturan Template WA
