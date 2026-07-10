# Panduan Implementasi & Pembaruan Desain Kartu Member Kustom (Custom Card Design)

Dokumen ini memberikan panduan langkah demi langkah untuk memperbarui desain Kartu Anggota (Member Card) pada Sistem CRM PT. Trijaya Motor. Panduan ini menjelaskan cara mengubah desain bawaan (gradien warna CSS) menjadi desain kustom berbasis template gambar menggunakan file aset yang telah disiapkan:
1. **`template_kosong.png`** (Sebagai gambar latar belakang kartu)
2. **`template_referensi.png`** (Sebagai acuan/referensi tata letak teks dan QR Code)

---

## Langkah 1: Persiapan Aset Gambar
Pastikan file gambar template telah berada di direktori publik proyek Anda. Folder yang digunakan adalah:
*   Jalur File Latar Belakang: `public/images/template_kosong.png`
*   Jalur File Referensi: `public/images/template_referensi.png`

---

## Langkah 2: Pembaruan Tampilan Preview Kartu (Web UI)
File preview digunakan oleh Admin untuk melihat kartu di browser sebelum dicetak. Kita perlu mengubah latar belakang kartu dari gradien warna menjadi gambar template.

### Lokasi File:
[preview.blade.php](file:///d:/TUGAS%20AKHIR/crm-dealer/resources/views/admin/member_cards/preview.blade.php)

### Panduan Modifikasi Kode:
Ubah kontainer kartu pada **baris 20** dari kelas warna Tailwind (`bg-gradient-to-r from-blue-500 to-indigo-600`) menggunakan inline CSS dengan direktif `asset()`:

```html
<!-- Sebelum: -->
<div class="w-96 h-56 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl shadow-2xl overflow-hidden relative text-white flex">

<!-- Sesudah (Menggunakan Gambar Latar Belakang): -->
<div class="w-[384px] h-[224px] rounded-xl shadow-2xl overflow-hidden relative text-white" 
     style="background-image: url('{{ asset('images/template_kosong.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
```

### Penyesuaian Tata Letak Elemen (Teks & QR Code):
Gunakan posisi absolut (`absolute`) untuk meletakkan teks nama, nomor member, dan QR Code di posisi yang sesuai dengan kotak kosong pada template:

```html
<!-- Contoh Penempatan Absolut Elemen di Halaman Preview -->
<div class="absolute" style="top: 20px; left: 24px;">
    <!-- Logo / Judul Kartu jika diperlukan -->
</div>

<div class="absolute" style="bottom: 40px; left: 24px; color: #333;">
    <!-- Nomor Member & Nama Pelanggan (Ganti warna teks agar kontras dengan template) -->
    <p class="text-sm tracking-widest font-semibold">{{ $memberCard->member_code }}</p>
    <p class="text-lg font-bold uppercase">{{ $customer->customer_name }}</p>
</div>

<div class="absolute" style="top: 40px; right: 24px;">
    <!-- Kontainer QR Code -->
    <div class="bg-white p-1 rounded shadow">
        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(80)->generate($memberCard->qr_token) !!}
    </div>
</div>
```

---

## Langkah 3: Pembaruan Tampilan Cetak Kartu (Ekspor PDF)
Proses pencetakan menggunakan DomPDF. Karena PDF di-generate di sisi server, kita harus menggunakan jalur lokal (`public_path`) agar gambar latar belakang dapat termuat dengan sempurna di dalam PDF.

### Lokasi File:
[print.blade.php](file:///d:/TUGAS%20AKHIR/crm-dealer/resources/views/admin/member_cards/print.blade.php)

### Panduan Modifikasi CSS:
Perbarui kelas `.card` di dalam tag `<style>` untuk memuat gambar latar belakang menggunakan `public_path()`:

```css
/* Sebelum: */
.card {
    width: 100%;
    height: 100%;
    border-radius: 10px;
    background: linear-gradient(to right, #3b82f6, #4f46e5);
    color: white;
    position: relative;
    overflow: hidden;
    box-sizing: border-box;
}

/* Sesudah: */
.card {
    width: 100%;
    height: 100%;
    border-radius: 8px;
    background-image: url('{{ public_path('images/template_kosong.png') }}');
    background-size: 100% 100%;
    background-repeat: no-repeat;
    position: relative;
    overflow: hidden;
    box-sizing: border-box;
}
```

### Mengatur Koordinat Elemen di PDF:
Karena PDF dicetak menggunakan struktur HTML/CSS murni oleh DomPDF, posisi mengambang (*float*) sebaiknya diganti dengan posisi absolut (`position: absolute`) agar letaknya presisi milimeter demi milimeter sesuai desain referensi:

```css
/* Ganti struktur kolom bawaan dengan koordinat absolut */
.left-col {
    position: absolute;
    left: 20px;
    bottom: 25px;
}

.right-col {
    position: absolute;
    right: 20px;
    top: 25px;
}

.member-code {
    font-size: 12px;
    font-weight: bold;
    color: #1a1a1a; /* Sesuaikan warna agar kontras dengan template */
    margin: 0;
}

.customer-name {
    font-size: 14px;
    font-weight: bold;
    color: #000000;
    margin-top: 2px;
}
```

---

## Langkah 4: Pengaturan Ukuran Standar Kartu CR-80 (Opsional)
Secara default, aplikasi menggunakan ukuran kertas `a8` lanskap. Jika Anda ingin mencetak kartu dengan ukuran standar kartu kredit/debit fisik (Standar **CR-80** dengan ukuran **85.60 mm x 53.98 mm**), Anda dapat mengubah konfigurasinya pada controller.

### Lokasi File:
[MemberCardController.php](file:///d:/TUGAS%20AKHIR/crm-dealer/app/Http/Controllers/MemberCardController.php)

### Panduan Modifikasi:
Ubah pemanggilan method `setPaper()` di dalam fungsi `print()` (baris 54-55):

```php
// Sebelum (A8 Landscape):
$pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.member_cards.print', compact('customer', 'memberCard'))
        ->setPaper('a8', 'landscape');

// Sesudah (Ukuran Kustom CR-80 Landscape - Ukuran dalam Poin/Points):
// Rumus Konversi: 1 mm = 2.83465 points
// Width: 85.60mm * 2.83465 = 242.64 pt
// Height: 53.98mm * 2.83465 = 153.01 pt
$paperSize = [0, 0, 242.64, 153.01];
$pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.member_cards.print', compact('customer', 'memberCard'))
        ->setPaper($paperSize, 'portrait'); // 'portrait' karena dimensi array sudah di-set landscape (lebar > tinggi)
```

---

## Langkah 5: Pengujian
Setelah melakukan modifikasi di atas, lakukan langkah verifikasi berikut:
1.  Buka web aplikasi CRM Dealer.
2.  Masuk ke menu **Customers** -> Pilih salah satu nama pelanggan.
3.  Klik **Preview Card** untuk melihat tampilan kartu digital (Langkah 2).
4.  Klik **Print Card / Print PDF** untuk mengunduh versi cetak (Langkah 3).
5.  Buka file PDF tersebut dan pastikan tata letak Nama Pelanggan, Nomor Member, dan QR Code telah sejajar sempurna dengan pola visual yang ada pada `template_referensi.png`.
