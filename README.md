# 🎓 Sistem Manajemen TK Teratai

Aplikasi manajemen sekolah untuk Taman Kanak-Kanak (TK) yang dibangun dengan Laravel 12. Sistem ini memudahkan pengelolaan data siswa, guru, jadwal, nilai, absensi, dan komunikasi antara guru dengan orang tua.

## ✨ Fitur Utama

### 👨‍💼 Admin
- Manajemen Data Siswa, Guru, dan Orang Tua
- Pengelolaan Jadwal dan Mata Pelajaran
- Kelola Jadwal Siswa per Kelas
- Publikasi Pengumuman

### 👨‍🏫 Guru
- Dashboard dengan statistik siswa dan jadwal
- Lihat Kelas dan Jadwal Mengajar
- Input Nilai dan Catatan Perilaku Siswa
- Kelola Absensi (termasuk bulk delete)
- Buat Laporan Lengkap untuk Orang Tua
- Komunikasi via Komentar di Laporan

### 👨‍👩‍👧 Orang Tua
- Dashboard perkembangan anak
- Lihat Profil dan Data Anak
- Akses Jadwal Belajar Anak
- Lihat Laporan Perkembangan Lengkap
- Komunikasi dengan Guru via Komentar
- Notifikasi Pengumuman

## 📋 Persyaratan Sistem

Sebelum memulai, pastikan komputer Anda sudah terinstal:

- **PHP** >= 8.3.7
- **Composer** (PHP Package Manager)
- **Node.js** >= 18.x dan **NPM** (untuk frontend assets)
- **MySQL** >= 8.0 atau **MariaDB** >= 10.x
- **Git** (untuk clone repository)

### 🔧 Cara Mengecek Versi

Buka **Command Prompt** atau **Terminal** dan jalankan:

```bash
php -v
composer -V
node -v
npm -v
mysql --version
git --version
```

Jika ada yang belum terinstal, silakan download dan instal dari:
- PHP: [https://www.php.net/downloads](https://www.php.net/downloads) atau gunakan [Laragon](https://laragon.org/download/)/[XAMPP](https://www.apachefriends.org/)
- Composer: [https://getcomposer.org/download/](https://getcomposer.org/download/)
- Node.js: [https://nodejs.org/](https://nodejs.org/) (pilih versi LTS)
- MySQL: sudah termasuk di Laragon/XAMPP
- Git: [https://git-scm.com/downloads](https://git-scm.com/downloads)

---

## 🚀 Panduan Instalasi (Langkah demi Langkah)

### 1️⃣ Clone Repository

Buka **Command Prompt** atau **Terminal**, lalu jalankan:

```bash
git clone https://github.com/AdenSahwaludin/simpelteratai.git
cd simpelteratai
```

### 2️⃣ Install Dependencies PHP

Instal semua package PHP yang dibutuhkan dengan Composer:

```bash
composer install
```

**Catatan:** Proses ini membutuhkan koneksi internet dan bisa memakan waktu 2-5 menit.

### 3️⃣ Install Dependencies Frontend

Instal package JavaScript/CSS dengan NPM:

```bash
npm install
```

### 4️⃣ Setup File Environment

Copy file `.env.example` menjadi `.env`:

**Windows (Command Prompt):**
```bash
copy .env.example .env
```

**Windows (PowerShell):**
```bash
Copy-Item .env.example .env
```

**macOS/Linux:**
```bash
cp .env.example .env
```

### 5️⃣ Generate Application Key

Generate key enkripsi untuk aplikasi:

```bash
php artisan key:generate
```

### 6️⃣ Konfigurasi Database

Buat database baru di MySQL/MariaDB:

1. Buka **phpMyAdmin** (jika pakai Laragon/XAMPP: `http://localhost/phpmyadmin`)
2. Klik **"New"** di sidebar kiri
3. Beri nama database: `tkt_teratai`
4. Collation: `utf8mb4_unicode_ci`
5. Klik **"Create"**

**Atau via Command Line:**

```bash
mysql -u root -p
```

Lalu ketik:

```sql
CREATE DATABASE tkt_teratai CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### 7️⃣ Edit File .env

Buka file `.env` dengan text editor (Notepad++, VS Code, dll), cari bagian database dan ubah:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tkt_teratai
DB_USERNAME=root
DB_PASSWORD=
```

**Catatan:**
- Jika menggunakan password MySQL, isi `DB_PASSWORD` dengan password Anda
- Jika pakai Laragon, biasanya tidak ada password (kosongkan)
- Jika pakai XAMPP, defaultnya juga tanpa password

### 8️⃣ Jalankan Migration & Seeder

Migration akan membuat struktur tabel, dan Seeder akan mengisi data awal:

```bash
php artisan migrate --seed
```

**Jika ada error:**
- Pastikan MySQL sudah running
- Cek kembali konfigurasi `.env` (DB_DATABASE, DB_USERNAME, DB_PASSWORD)
- Pastikan database `tkt_teratai` sudah dibuat

### 9️⃣ Build Frontend Assets

Compile CSS dan JavaScript dengan Vite:

**Untuk Development (dengan hot reload):**
```bash
npm run dev
```

**Untuk Production (file optimized):**
```bash
npm run build
```

**Catatan:** Jika menggunakan `npm run dev`, biarkan terminal tetap terbuka.

### 🔟 Jalankan Aplikasi

Buka terminal/command prompt baru (jika `npm run dev` masih jalan), lalu:

```bash
php artisan serve
```

Aplikasi akan berjalan di: **http://127.0.0.1:8000** atau **http://localhost:8000**

---

## 🔐 Akun Default untuk Login

Setelah instalasi selesai, Anda bisa login dengan akun berikut:

### Admin
- **Email:** `admin@teratai.sch.id`
- **Password:** `password123`
- **URL:** http://localhost:8000/admin/login

### Guru
- **Email:** `guru@teratai.sch.id`
- **Password:** `password123`
- **URL:** http://localhost:8000/guru/login

### Orang Tua
- **Email:** `orangtua@teratai.sch.id`
- **Password:** `password123`
- **URL:** http://localhost:8000/orangtua/login

**⚠️ PENTING:** Segera ubah password default setelah login pertama kali!

---

## 🛠️ Troubleshooting (Mengatasi Error Umum)

### ❌ Error: "Route [login] not defined"
**Solusi:**
```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

### ❌ Error: "SQLSTATE[HY000] [1045] Access denied"
**Solusi:**
- Cek username dan password MySQL di file `.env`
- Pastikan MySQL service sudah running

### ❌ Error: "SQLSTATE[HY000] [2002] No such file or directory"
**Solusi:**
- Pastikan MySQL/MariaDB sudah dijalankan
- Di Laragon: Klik "Start All"
- Di XAMPP: Start Apache dan MySQL

### ❌ Error: "npm ERR! code ENOENT"
**Solusi:**
```bash
rm -rf node_modules package-lock.json
npm install
```

### ❌ Error: "Class 'X' not found"
**Solusi:**
```bash
composer dump-autoload
php artisan optimize:clear
```

### ❌ Frontend tidak tampil dengan baik / CSS tidak load
**Solusi:**
- Pastikan `npm run dev` atau `npm run build` sudah dijalankan
- Cek file `public/hot` ada atau tidak
- Hapus cache browser (Ctrl+F5)

### ❌ Error: "Permission denied" (Linux/Mac)
**Solusi:**
```bash
chmod -R 775 storage bootstrap/cache
chown -R $USER:www-data storage bootstrap/cache
```

---

## 📁 Struktur Project

```
simpelteratai/
├── app/                    # Logika aplikasi (Controllers, Models)
│   ├── Http/Controllers/   # Controller untuk Admin, Guru, OrangTua
│   ├── Models/             # Eloquent Models (Siswa, Guru, dll)
│   └── Providers/          # Service Providers
├── database/
│   ├── migrations/         # File migration database
│   └── seeders/            # Data awal (dummy data)
├── resources/
│   ├── views/              # Blade templates (UI)
│   │   ├── admin/          # Views untuk Admin
│   │   ├── guru/           # Views untuk Guru
│   │   ├── orangtua/       # Views untuk Orang Tua
│   │   └── components/     # Reusable components
│   ├── css/                # Tailwind CSS
│   └── js/                 # JavaScript files
├── routes/
│   └── web.php             # Definisi routes aplikasi
├── public/                 # Assets publik (images, compiled CSS/JS)
├── storage/                # File upload, logs, cache
└── .env                    # Konfigurasi environment
```

---

## 🔄 Update Project (Jika Ada Perubahan dari Git)

Jika ada update dari repository, jalankan:

```bash
git pull origin main
composer install
npm install
php artisan migrate
npm run build
php artisan optimize:clear
```

---

## 📝 Development Commands

### Jalankan Development Server
```bash
php artisan serve
```

### Compile Assets (dengan hot reload)
```bash
npm run dev
```

### Build Production Assets
```bash
npm run build
```

### Jalankan Migration Baru
```bash
php artisan migrate
```

### Rollback Migration
```bash
php artisan migrate:rollback
```

### Reset Database (Fresh Install)
```bash
php artisan migrate:fresh --seed
```

### Clear All Cache
```bash
php artisan optimize:clear
```

### Format Code (Laravel Pint)
```bash
vendor/bin/pint
```

---

## 🧪 Testing

Jalankan test suite dengan Pest:

```bash
php artisan test
```

Untuk test spesifik:
```bash
php artisan test --filter=NamaTest
```

---

## 📚 Teknologi yang Digunakan

- **Backend:** Laravel 12 (PHP 8.3)
- **Frontend:** Blade Templates + Alpine.js
- **CSS:** Tailwind CSS v4
- **Database:** MySQL/MariaDB
- **Build Tool:** Vite
- **Testing:** Pest v4
- **Icons:** Font Awesome 6

---

## 👨‍💻 Developer

**Aden Sahwaludin**
- GitHub: [@AdenSahwaludin](https://github.com/AdenSahwaludin)
- Repository: [simpelteratai](https://github.com/AdenSahwaludin/simpelteratai)

---

## 📄 Lisensi

Project ini menggunakan lisensi [MIT](https://opensource.org/licenses/MIT).

---

## 🆘 Butuh Bantuan?

Jika mengalami kesulitan atau menemukan bug:

1. Cek bagian **Troubleshooting** di atas
2. Buka [Issues](https://github.com/AdenSahwaludin/simpelteratai/issues) di GitHub
3. Buat issue baru dengan deskripsi error yang detail

---

**Selamat Menggunakan Sistem Manajemen TK Teratai! 🎉**

