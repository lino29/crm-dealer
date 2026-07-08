# Sistem Josia - CRM Dealer PT. Trijaya Motor

Sistem Manajemen Hubungan Pelanggan (CRM) untuk dealer motor PT. Trijaya Motor. Sistem ini dirancang untuk mengelola data pelanggan, kendaraan, riwayat servis, jadwal servis, serta pengiriman pengingat (reminder) servis berkala via WhatsApp. Sistem ini juga memiliki fitur Kartu Member dengan sistem QR Code/Barcode.

## Tech Stack
- **PHP Version**: 8.3+
- **Framework**: Laravel 13.x
- **Frontend**: Blade Templating, Tailwind CSS, Alpine.js
- **Database**: MySQL / MariaDB
- **Visualisasi & Scanner**: Chart.js v4 (via CDN), `html5-qrcode` (via npm/CDN)
- **Export**: `Maatwebsite/Laravel-Excel` dan `Barryvdh/Laravel-DomPDF`

## Fitur Utama
1. **Manajemen Pengguna & Role Access**: Hak akses untuk Admin dan Pimpinan (Leader).
2. **Manajemen Dealer**: Data multi-cabang dealer.
3. **Manajemen Pelanggan & Kendaraan**: Data relasional pelanggan dan kendaraan mereka.
4. **Kartu Member & QR Code**: Generate kode member unik dan Token QR.
5. **Scanner QR Code**: Fitur pindai kartu member untuk melihat data pelanggan dan mencatat log kunjungan.
6. **Riwayat & Jadwal Servis**: Pencatatan riwayat servis dan penjadwalan servis berikutnya secara otomatis.
7. **WhatsApp Reminder**: Pengiriman notifikasi pengingat jadwal servis via Gateway WhatsApp.
8. **Dashboard & Laporan**: Laporan lengkap untuk pimpinan yang dapat diexpor ke PDF dan Excel.

## Panduan Instalasi (Local Development)

### Persyaratan Sistem
Pastikan sistem Anda sudah memiliki:
- PHP >= 8.3
- Composer
- Node.js & npm
- MySQL atau MariaDB

### Langkah-langkah Setup
1. **Clone Repository** (jika menggunakan Git) atau ekstrak file sumber.
2. **Install dependensi PHP**:
   ```bash
   composer install
   ```
3. **Install dependensi Frontend**:
   ```bash
   npm install
   ```
4. **Siapkan Environment**:
   Salin file konfigurasi environment:
   ```bash
   cp .env.example .env
   ```
   Atur koneksi database Anda di `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sistem_josia
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   Lalu *generate application key*:
   ```bash
   php artisan key:generate
   ```
5. **Jalankan Migrasi & Seeder Database**:
   Ini akan membuat tabel beserta akun default.
   ```bash
   php artisan migrate:fresh --seed
   ```
6. **Build Aset Frontend (Penting!)**:
   Sistem ini menggunakan Vite dan Tailwind CSS. Anda wajib menjalankan build untuk men-generate file CSS yang berisi class desain terbaru.
   ```bash
   npm run build
   ```
7. **Jalankan Server Lokal**:
   ```bash
   php artisan serve
   ```
   Aplikasi akan berjalan pada `http://127.0.0.1:8000`.

## Akun Default
Setelah menjalankan seeder, Anda dapat masuk menggunakan akun berikut:
- **Admin**:
  - Email: `admin@trijaya.com` / Username: `admin`
  - Password: `password`
- **Pimpinan (Leader)**:
  - Email: `leader@trijaya.com` / Username: `leader`
  - Password: `password`

## Menjalankan Antrean (Queue) untuk WhatsApp
Pengiriman WhatsApp Reminder berjalan secara *asynchronous* menggunakan Laravel Queue agar tidak memblokir antarmuka web.

Untuk memproses notifikasi WhatsApp yang berada dalam status `pending`:
```bash
php artisan queue:work
```

## Menjalankan Scheduler (Otomatisasi)
Jika sistem dikembangkan lebih lanjut untuk mengirimkan reminder secara otomatis setiap hari, jalankan:
```bash
php artisan schedule:work
```

## Catatan Konfigurasi Khusus

### 1. WhatsApp Gateway
Sistem menggunakan servis pihak ketiga (seperti Fonnte atau Watzap) untuk mengirim pesan WhatsApp. 
Jika variabel `WHATSAPP_API_TOKEN` pada file `.env` dikosongkan, sistem akan berjalan dalam **Dummy Mode** (pesan hanya dicatat di file `storage/logs/laravel.log`).
```env
WHATSAPP_API_TOKEN=token_api_anda_disini
```

### 2. Scanner QR Code
Sistem memanfaatkan token UUID mentah (`qr_token`) di dalam QR Code untuk mencegah kebocoran data pelanggan. 
Scanner diakses dari sisi *client-side* (contoh menggunakan library `html5-qrcode` jika diintegrasikan langsung). Pastikan memberikan izin akses kamera pada browser saat menggunakan halaman *Scan Member Card*.

### 3. PDF & Excel Export
Untuk kelancaran *export* PDF menggunakan `dompdf`, pastikan ekstensi PHP `gd` atau `imagick` aktif di `php.ini`. Laporan Excel menggunakan Laravel Excel sehingga sangat responsif.

---
*Dokumentasi ini telah disesuaikan dengan Software Requirement Specification (SRS) Sistem Josia.*
