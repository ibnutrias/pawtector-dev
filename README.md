# Pawtector
Pawtector adalah aplikasi web komprehensif untuk mengelola fasilitas perawatan hewan peliharaan, "PawPark". Aplikasi ini memberikan pengalaman yang mulus bagi pemilik hewan untuk memesan layanan dan bagi administrator untuk mengelola operasional, melacak janji temu, serta melihat analisis data.

## ✨ Fitur

### Untuk Pemilik Hewan (Pengguna)
- **Manajemen Akun**: Pendaftaran dan login pengguna yang aman.
- **Dashboard Pawhub**: Dasbor yang dipersonalisasi untuk mengelola hewan peliharaan dan pemesanan.
- **Profil Hewan**: Menambah, mengedit, dan menghapus profil detail untuk setiap hewan peliharaan.
- **Pemesanan Layanan**: Menjadwalkan janji temu dengan mudah untuk layanan *Boarding* (Penitipan), *Daycare* (Penitipan Harian), dan *Grooming* (Perawatan).
- **Laporan Aktivitas Langsung**: Melihat pembaruan *real-time* mengenai aktivitas hewan Anda (makan, bermain, perawatan) dan catatan dari staf selama mereka menginap.
- **Riwayat Pemesanan**: Melacak janji temu yang akan datang dan yang sudah lewat beserta statusnya (Menunggu, Aktif, Selesai, Dibatalkan).
- **Kustomisasi Profil**: Memperbarui informasi akun pribadi.

### Untuk Administrator
- **Dashboard Admin**: Tinjauan statistik utama termasuk total klien, permintaan yang tertunda (*pending*), dan sesi yang sedang aktif.
- **Manajemen Pemesanan**: Melihat semua janji temu pengguna, memperbarui status, dan mengelola jadwal.
- **Pelaporan Aktivitas**: Membuat dan memperbarui laporan aktivitas harian untuk hewan yang sedang dalam perawatan.
- **Analitik & Laporan**:
    - Visualisasi tren pemesanan bulanan dengan diagram garis.
    - Analisis popularitas layanan dengan diagram donat (*Boarding, Daycare, Grooming*).
    - Ekspor data laporan ke CSV atau PDF untuk pengarsipan.
- **Pembuatan Tanda Terima**: Mencetak struk rinci untuk layanan yang telah selesai.

## 🛠️ Teknologi yang Digunakan

- **Backend**: PHP
- **Database**: MySQL / MariaDB
- **Frontend**: HTML5, Bootstrap 5, JavaScript
- **Library**: Chart.js (untuk analitik dashboard admin)

## 🚀 Memulai (Getting Started)

Untuk menjalankan salinan lokal di komputer Anda, ikuti langkah-langkah sederhana berikut.

### Prasyarat

- Lingkungan server web seperti XAMPP, WAMP, atau MAMP.
- PHP
- MySQL atau MariaDB

### Instalasi

1.  **Clone repositori:**
    ```sh
    git clone [https://github.com/ibnutrias/pawtector-dev.git](https://github.com/ibnutrias/pawtector-dev.git)
    ```

2.  **Masuk ke direktori proyek:**
    ```sh
    cd pawtector-dev
    ```

3.  **Pengaturan Database:**
    a. Buat database baru di server MySQL Anda (misalnya, `pawpark`).
    b. Impor struktur tabel dengan mengeksekusi file SQL yang berada di direktori `/sql` dengan urutan sebagai berikut:
    - `buat_tabel_user.sql`
    - `buat_tabel_pets.sql`
    - `buat_tabel_booking.sql`

4.  **Konfigurasi Koneksi Database:**
    a. Buka file `core/koneksi.php`.
    b. Perbarui kredensial database agar sesuai dengan pengaturan lokal Anda:
    ```php
    $DB_SERVER = "127.0.0.1";
    $DB_USERNAME = "username_anda";
    $DB_PASSWORD = "password_anda";
    $DB_DATABASE = "pawpark";
    ```

5.  **Jalankan Aplikasi:**
    a. Letakkan folder proyek di direktori root server web Anda (misalnya, `htdocs` untuk XAMPP).
    b. Buka browser web Anda dan kunjungi `http://localhost/pawtector-dev`.

### Membuat Akun Admin

Untuk mengakses panel admin, Anda perlu mengubah *role* (peran) pengguna menjadi `0`.

1.  Daftarkan pengguna baru melalui halaman registrasi aplikasi.
2.  Di klien database Anda (seperti phpMyAdmin), cari pengguna yang baru dibuat di tabel `users`.
3.  Ubah nilai kolom `role` untuk pengguna tersebut dari `1` menjadi `0`.
4.  Login dengan kredensial pengguna tersebut untuk mengakses Dashboard Admin di `/admin`.

## 📂 Struktur Proyek

Repositori ini diatur sebagai berikut:
```
└── pawtector-dev/
    ├── admin/            # Dashboard admin dan halaman manajemen
    ├── assets/           # File statis (gambar, video)
    ├── beranda/          # Komponen untuk beranda publik
    ├── core/             # Logika inti: koneksi database, fungsi layout
    ├── komponen/         # Komponen UI yang dapat digunakan kembali (navbar, footer)
    ├── masuk/            # Logika halaman login
    ├── pawhub/           # Dashboard pengguna untuk hewan dan pemesanan
    │   ├── bookings/     # Manajemen pemesanan pengguna
    │   ├── my-pets/      # Manajemen profil hewan pengguna
    │   └── you/          # Manajemen profil pengguna
    ├── registrasi/       # Logika halaman registrasi
    ├── sql/              # File SQL untuk skema database
    ├── index.php         # Titik masuk utama untuk beranda
    ├── logout.php        # Skrip logout pengguna
    ├── pawhub.db         # File database SQLite (diabaikan oleh .gitignore, digunakan untuk dev)
    └── tentang.php       # Halaman "Tentang Kami"
```
