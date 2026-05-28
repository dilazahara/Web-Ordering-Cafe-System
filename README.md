# Sistem Pemesanan Menu Cafe Momoo Juice Bar Coffee Windsor Batam Berbasis Web

## Deskripsi

Sistem Pemesanan Cafe Momoo Berbasis Web adalah aplikasi yang dibuat untuk mempermudah proses pemesanan makanan dan minuman di cafe secara digital.

Aplikasi ini memungkinkan customer melihat menu, menambahkan pesanan ke keranjang, melakukan checkout, memilih metode pembayaran, dan memantau status pesanan secara online.

Pesanan yang masuk akan diproses oleh dapur, dikonfirmasi oleh kasir, dan diselesaikan oleh pelayan hingga pesanan diterima customer.

Project ini dibuat menggunakan framework **Laravel** sebagai bagian dari **Tugas Akhir / Project Pengembangan Aplikasi Web**.

---

# Fitur Utama

## Customer

* Registrasi akun
* Login customer
* Melihat daftar menu makanan & minuman
* Melihat detail menu
* Menambahkan menu ke keranjang
* Menambahkan topping / add-ons
* Checkout pesanan
* Memilih metode pembayaran
* Melihat status pesanan
* Melihat riwayat transaksi

---

## Admin

* Dashboard admin
* CRUD menu
* CRUD kategori menu
* CRUD meja
* CRUD add-ons / topping
* CRUD metode pembayaran
* Kelola data user
* Monitoring transaksi
* Laporan penjualan
* Export laporan PDF

---

## Kasir

* Melihat daftar pesanan
* Verifikasi pembayaran
* Mengelola transaksi pembayaran
* Eksport laporan harian PDF

---

## Dapur

* Melihat pesanan masuk
* Memproses pesanan
* Update status pesanan

---

## Pelayan

* Melihat pesanan siap antar
* Mengantar pesanan ke meja customer
* Menyelesaikan status pesanan

---

# Teknologi yang Digunakan

Project ini dibangun menggunakan:

* PHP 8+
* Laravel
* MySQL / MariaDB
* Blade Template
* Bootstrap / Tailwind CSS
* JavaScript
* Composer
* NPM / Vite

---

# Instalasi Project

## Clone Repository

```bash
git clone https://github.com/username/nama-project.git
cd nama-project
```

---

## Install Dependency

```bash
composer install
```

---

## Install Frontend Dependency

```bash
npm install
```

---

## Copy File Environment

```bash
cp .env.example .env
```

---

## Generate Application Key

```bash
php artisan key:generate
```

---

## Konfigurasi Database

Edit file `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=root
DB_PASSWORD=
```

---

## Jalankan Migration

```bash
php artisan migrate
```

---

## Jalankan Seeder

```bash
php artisan db:seed
```

---

## Menjalankan Project

Jalankan server Laravel:

```bash
php artisan serve
```

Aplikasi akan berjalan di:

```bash
http://127.0.0.1:8000
```

Jika ingin diakses dari perangkat lain atau melalui internet/public URL, jalankan Ngrok:

```bash
ngrok http 8000
```

Lalu gunakan URL Ngrok yang muncul, contoh:

```bash
https://xxxx-xxxx.ngrok-free.app
```


# Alur Sistem

```text
Customer Memesan Menu
↓
Pesanan Masuk ke Sistem
↓
Dapur Memproses Pesanan
↓
Kasir Mengonfirmasi Pembayaran
↓
Pelayan Mengantar Pesanan
↓
Pesanan Selesai
```

---

# Struktur Role User

Sistem memiliki beberapa role user:

* Admin
* Customer
* Kasir
* Dapur
* Pelayan

Masing-masing role memiliki akses sesuai kebutuhan operasional cafe.

---

# Tujuan Sistem

Tujuan dibuatnya aplikasi ini adalah:

* Mempermudah proses pemesanan makanan dan minuman
* Mengurangi kesalahan pencatatan pesanan
* Mempercepat pelayanan restoran
* Membantu pengelolaan transaksi secara digital
* Menyediakan laporan penjualan secara otomatis
* Meningkatkan efisiensi operasional cafe

---

# Dokumentasi Sistem

Dokumentasi tampilan sistem meliputi:

* Halaman Login
* Dashboard Admin
* Daftar Menu
* Cart / Keranjang
* Checkout
* Dashboard Kasir
* Dashboard Dapur
* Dashboard Pelayan
* Laporan Penjualan

---

# Author

**Nama:** Faradilla Zahara
**NIM:** 3312301012
**Program Studi:** Teknik Informatika
**Universitas:** Politeknik Negeri Batam

---

# License

Project ini dibuat untuk kebutuhan akademik, pembelajaran, dan tugas akhir.
