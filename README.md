# Sistem Operasi Bisnis (BIOS) Franchise Mie Ayam "KukuruKuy"

Selamat datang di sistem manajemen franchise **KukuruKuy**. Ini adalah aplikasi web lengkap yang dirancang untuk mengelola seluruh aspek operasional bisnis mie ayam, mulai dari kantor pusat hingga ke setiap outlet cabang.

Aplikasi ini dibangun dengan arsitektur modern untuk memastikan skalabilitas, efisiensi, dan kemudahan penggunaan.

---

## ✨ Fitur Utama

### 🧠 Panel Admin Pusat (BIOS)
Sebuah *Business Intelligence Operating System* yang dibangun menggunakan **Filament 3**. Fitur ini memungkinkan admin pusat untuk:

- Mengelola data master
- Melihat laporan penjualan dari semua cabang
- Mengelola produk, resep, dan pengguna sistem

### 🌐 Arsitektur Multi-Domain

Aplikasi ini berjalan di beberapa subdomain untuk memisahkan fungsi dan antarmuka pengguna:

- `app.kukurukuy.test`: Panel Admin (BIOS)
- `kasir.kukurukuy.test`: Antarmuka Point of Sale (POS)
- `stok.kukurukuy.test`: Panel manajemen stok untuk manajer cabang

### 🏢 Manajemen Franchise
Mengelola data master untuk setiap cabang, termasuk:

- Alamat
- Kontak
- Karyawan yang ditugaskan

### 🍜 Manajemen Produk & Resep
Admin dapat:

- Membuat produk baru
- Menentukan resep bahan baku untuk setiap porsi
- Otomatis mengurangi stok saat transaksi penjualan terjadi

### 💳 Point of Sale (POS) Modern
- Antarmuka kasir yang intuitif dan cepat
- Terintegrasi langsung dengan sistem stok

### 📦 Manajemen Stok Real-time
Setiap transaksi penjualan akan otomatis mengurangi stok bahan baku cabang.

### 🔐 Autentikasi Berbasis Peran
- Role-based access: admin, manager, cashier
- Login dari subdomain mana pun akan diarahkan sesuai peran pengguna

### 🔔 Notifikasi Real-time *(Opsional)*
- Menggunakan **Laravel Reverb**
- Untuk notifikasi instan ke panel admin (misalnya saat ada pesanan baru)

---

## 🛠️ Teknologi yang Digunakan

- **Backend**: Laravel 11
- **Frontend**: Blade, Tailwind CSS, Vanilla JavaScript
- **Panel Admin**: Filament 3
- **Autentikasi**: Laravel Breeze
- **Server Real-time**: Laravel Reverb (Pusher Protocol)
- **Database**: MySQL (atau database SQL lainnya)

---

## ⚙️ Panduan Instalasi Lokal

### 1. Prasyarat

- PHP 8.2+
- Composer
- Node.js & NPM
- Server Database (MySQL, MariaDB, dll)

### 2. Kloning Repositori

```bash
git clone [URL-repositori-Anda] kukurukuy
cd kukurukuy
```

### 3. Instalasi Dependensi

```bash
composer install
npm install
```

### 4. Konfigurasi Environment

Salin file `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

Edit file `.env` dan sesuaikan konfigurasi database Anda:

```
DB_DATABASE=kukurukuy
DB_USERNAME=root
DB_PASSWORD=
SESSION_DOMAIN=.kukurukuy.test
```

Generate key aplikasi:

```bash
php artisan key:generate
```

### 5. Konfigurasi Host Lokal

Edit file `hosts` pada komputer Anda:

- **Windows**: `C:\Windows\System32\drivers\etc\hosts`
- **Mac/Linux**: `/etc/hosts`

Tambahkan baris berikut:

```
127.0.0.1   app.kukurukuy.test
127.0.0.1   kasir.kukurukuy.test
127.0.0.1   stok.kukurukuy.test
```

### 6. Migrasi dan Seeding Database

Buat database sesuai `.env`, lalu jalankan:

```bash
php artisan migrate:fresh
php artisan db:seed # opsional
```

### 7. Buat User Admin

```bash
php artisan make:filament-user
```

Masukkan nama, email (misalnya: `admin@kukurukuy.com`), dan password.

---

## 🚀 Menjalankan Aplikasi

Buka 3 terminal berbeda di root direktori proyek:

### Terminal 1 – Web Server Laravel

```bash
php artisan serve
```

### Terminal 2 – Compiler Aset Frontend

```bash
npm run dev
```

### Terminal 3 – Server Real-time Reverb *(jika digunakan)*

```bash
php artisan reverb:start
```

---

## 🌐 Akses Aplikasi

- **Panel Admin (BIOS)**: [http://app.kukurukuy.test:8000](http://app.kukurukuy.test:8000)
- **Antarmuka Kasir**: [http://kasir.kukurukuy.test:8000](http://kasir.kukurukuy.test:8000)
- **Panel Stok**: [http://stok.kukurukuy.test:8000](http://stok.kukurukuy.test:8000)

> Login dapat dilakukan dari subdomain mana pun. Sistem akan secara otomatis mengarahkan pengguna berdasarkan perannya setelah login berhasil.

---

## 📄 Lisensi

Proyek ini berada di bawah lisensi [MIT](LICENSE).