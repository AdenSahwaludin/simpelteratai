# Testing Seeder Guide

Dokumentasi lengkap untuk menggunakan seeder dalam project untuk testing.

## Struktur Seeder

Seeder diatur dalam urutan yang tepat untuk menghindari foreign key constraint errors:

1. **RolePermissionSeeder** - Setup roles dan permissions
2. **UserSeeder** - Create 3 users dengan roles berbeda
3. **KategoriLokasiSeeder** - Create 7 kategori dan 5 lokasi
4. **BarangTestSeeder** - Generate 50 barang dengan factory
5. **TransaksiBarangTestSeeder** - Create 30 transaksi (15 masuk + 15 keluar + pending)
6. **LogAktivitasTestSeeder** - Generate 60 log aktivitas dengan berbagai jenis

## Perintah Seeding

### Fresh Seed (Drop semua tabel dan seed ulang)

```bash
php artisan migrate:fresh --seed
# atau dengan --no-interaction untuk CI/CD
php artisan migrate:fresh --seed --no-interaction
```

### Seed Tanpa Drop

```bash
php artisan db:seed
```

### Seed Seeder Spesifik

```bash
# Seed hanya kategori dan lokasi
php artisan db:seed --class=KategoriLokasiSeeder

# Seed hanya barang
php artisan db:seed --class=BarangTestSeeder

# Seed hanya transaksi
php artisan db:seed --class=TransaksiBarangTestSeeder
```

## Factory Yang Tersedia

### 1. UserFactory

**File:** `database/factories/UserFactory.php`

**Method:**

-   `petugasInventaris()` - Create user dengan role Petugas Inventaris

**Contoh penggunaan:**

```php
User::factory(5)->create(); // Create 5 users
User::factory()->petugasInventaris()->create(); // User dengan role spesifik
```

### 2. KategoriFactory

**File:** `database/factories/KategoriFactory.php`

**Generate:** Kategori dengan nama acak dari daftar kategori sekolah

**Contoh penggunaan:**

```php
Kategori::factory(10)->create(); // Create 10 kategori
```

### 3. LokasiFactory

**File:** `database/factories/LokasiFactory.php`

**Generate:** Lokasi dengan nama, gedung, lantai, dan keterangan acak

**Contoh penggunaan:**

```php
Lokasi::factory(5)->create(); // Create 5 lokasi
```

### 4. BarangFactory

**File:** `database/factories/BarangFactory.php`

**Generate:** Barang dengan relasi ke Kategori dan Lokasi

**Fields yang di-generate:**

-   Kode barang otomatis (BRG000001, BRG000002, dst)
-   Nama barang random
-   Kategori random
-   Lokasi random
-   Stok 5-200
-   Harga 10.000-500.000
-   Status: baik/rusak/hilang
-   Merk, satuan, tanggal pembelian

**Contoh penggunaan:**

```php
Barang::factory(30)->create(); // Create 30 barang
Barang::factory(1)->create(); // Create 1 barang
```

### 5. TransaksiBarangFactory

**File:** `database/factories/TransaksiBarangFactory.php`

**Method:**

-   `masuk()` - Transaction type = masuk (input)
-   `keluar()` - Transaction type = keluar (output)
-   `pending()` - Status = pending
-   `approved()` - Status = approved dengan approved_by dan approved_at

**Contoh penggunaan:**

```php
TransaksiBarang::factory(10)->masuk()->create(); // 10 transaksi masuk
TransaksiBarang::factory(5)->keluar()->approved()->create(); // 5 transaksi keluar approved
```

### 6. LogAktivitasFactory

**File:** `database/factories/LogAktivitasFactory.php`

**Method:**

-   `createActivity()` - Activity = create
-   `updateActivity()` - Activity = update
-   `deleteActivity()` - Activity = delete
-   `login()` - Activity = login

**Contoh penggunaan:**

```php
LogAktivitas::factory(20)->createActivity()->create(); // 20 create logs
LogAktivitas::factory(15)->login()->create(); // 15 login logs
```

## Test Data Summary

Setelah menjalankan `migrate:fresh --seed`, berikut data yang dibuat:

```
Users: 3
  • admin@inventaris.test (Admin Sistem)
  • petugas@inventaris.test (Petugas Inventaris)
  • kepala@inventaris.test (Kepala Sekolah)

Kategori: 7
  • Elektronik
  • Furniture
  • Alat Tulis Kantor
  • Alat Kebersihan
  • Peralatan Olahraga
  • Buku Perpustakaan
  • Alat Laboratorium

Lokasi: 5
  • Gudang Penyimpanan
  • Ruang Kepala Sekolah
  • Laboratorium Komputer
  • Perpustakaan
  • Ruang Kelas

Barang: 50 (generated dengan factory)

Transaksi: 30
  • 15 transaksi masuk (approved)
  • 10 transaksi keluar (approved)
  • 5 transaksi pending

Log Aktivitas: 60+
  • Login logs
  • Create logs
  • Update logs
  • Delete logs
```

## Login Credentials untuk Testing

| Email                   | Password | Role               |
| ----------------------- | -------- | ------------------ |
| admin@inventaris.test   | password | Admin Sistem       |
| petugas@inventaris.test | password | Petugas Inventaris |
| kepala@inventaris.test  | password | Kepala Sekolah     |

## Tips Testing

### 1. Reset Database dan Seed

```bash
php artisan migrate:fresh --seed
```

### 2. Seed Spesifik Tabel Saja

Jika hanya ingin menambah data tanpa menghapus existing data:

```bash
# Tambah 20 barang baru
php artisan tinker
>>> Barang::factory(20)->create()
```

### 3. Buat Test Data Custom

```bash
php artisan tinker
# Create kategori
>>> Kategori::factory(3)->create()

# Create lokasi
>>> Lokasi::factory(2)->create()

# Create barang dengan relasi
>>> Barang::factory(50)->create()

# Create transaksi
>>> TransaksiBarang::factory(20)->masuk()->approved()->create()
```

### 4. Query Data untuk Verifikasi

```bash
php artisan tinker
>>> User::with('roles')->get()
>>> Barang::with('kategori', 'lokasi')->get()
>>> TransaksiBarang::with('barang', 'user')->get()
>>> LogAktivitas::with('user')->get()
```

### 5. Run Tests

```bash
# Run semua test
php artisan test

# Run feature test spesifik
php artisan test tests/Feature/BarangTest.php

# Run dengan filter
php artisan test --filter=testCreateBarang
```

## Troubleshooting

### Error: "Foreign key constraint fails"

Pastikan seeders dijalankan dalam urutan yang benar:

1. Roles/Permissions
2. Users
3. Kategori/Lokasi (master data)
4. Barang (FK ke Kategori, Lokasi)
5. Transaksi (FK ke Barang, User)
6. Log (FK ke User)

### Error: "Stok tidak mencukupi"

Ini terjadi saat membuat transaksi keluar tanpa stok. Solusi:

-   Buat transaksi masuk dulu (sudah ditangani di TransaksiBarangTestSeeder)
-   Atau disable observer saat seeding jika diperlukan

### Database Locked

Jalankan:

```bash
php artisan migrate:fresh --seed
# bukan migrate:rollback
```

## Menambah Data Baru ke Seeder

Jika ingin menambah seeder baru:

1. Create seeder file:

```bash
php artisan make:seeder CustomDataSeeder
```

2. Edit `DatabaseSeeder.php`:

```php
public function run(): void
{
    $this->call([
        // ... existing seeders ...
        CustomDataSeeder::class, // Add di sini
    ]);
}
```

3. Run:

```bash
php artisan migrate:fresh --seed
```

---

**Last Updated:** 29 December 2025  
**Laravel Version:** 12.43.1  
**PHP Version:** 8.3.7
