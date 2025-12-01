# Sistem Pertemuan Mingguan Otomatis

## Tanggal: 30 November 2025

## Konsep Sistem

Sistem jadwal diperbaiki agar setiap jadwal mata pelajaran **otomatis menghasilkan 14 pertemuan** (fixed 1 semester), dan **seluruh siswa di kelas tersebut otomatis terdaftar** pada semua pertemuan untuk mempermudah absensi mingguan.

## Struktur Database

### Tabel `jadwal` (Jadwal Mingguan)

```sql
CREATE TABLE jadwal (
    id_jadwal VARCHAR(3) PRIMARY KEY,
    id_guru VARCHAR(3),
    id_mata_pelajaran VARCHAR(3),
    ruang VARCHAR(50),
    waktu TIME,
    hari ENUM('Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'), -- BARU
    kelas VARCHAR(20), -- BARU
    tanggal_mulai DATE, -- BARU (Tanggal mulai semester)
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

**Fungsi**: Menyimpan jadwal mingguan (contoh: Matematika, Senin 08:00, Kelas A1)

### Tabel `pertemuan` (14 Pertemuan per Jadwal) - **BARU**

```sql
CREATE TABLE pertemuan (
    id_pertemuan VARCHAR(10) PRIMARY KEY, -- Format: J01-P01, J01-P02, ..., J01-P14
    id_jadwal VARCHAR(3),
    pertemuan_ke TINYINT, -- 1-14
    tanggal DATE, -- Tanggal spesifik pertemuan
    materi TEXT,
    status ENUM('terjadwal', 'berlangsung', 'selesai', 'dibatalkan'),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (id_jadwal) REFERENCES jadwal(id_jadwal) ON DELETE CASCADE,
    UNIQUE KEY (id_jadwal, pertemuan_ke)
);
```

**Fungsi**: Menyimpan 14 pertemuan otomatis untuk setiap jadwal dengan tanggal spesifik

### Tabel `absensi` (Diubah)

```sql
-- SEBELUM:
CREATE TABLE absensi (
    id_absensi VARCHAR(4) PRIMARY KEY,
    id_siswa VARCHAR(4),
    id_jadwal VARCHAR(3), -- ❌ Link langsung ke jadwal
    tanggal DATE, -- ❌ Manual input tanggal
    status_kehadiran ENUM('hadir', 'izin', 'sakit', 'alpha'),
    ...
);

-- SESUDAH:
CREATE TABLE absensi (
    id_absensi VARCHAR(4) PRIMARY KEY,
    id_siswa VARCHAR(4),
    id_pertemuan VARCHAR(10), -- ✅ Link ke pertemuan
    status_kehadiran ENUM('belum_absen', 'hadir', 'izin', 'sakit', 'alpha'), -- ✅ Tambah status 'belum_absen'
    ...
);
```

**Perubahan**:

-   Absensi sekarang link ke `pertemuan`, bukan `jadwal`
-   Tanggal otomatis dari pertemuan
-   Status default: `belum_absen`

## Alur Kerja

### 1. Admin Membuat Jadwal Mingguan

Admin hanya perlu input:

-   Mata Pelajaran
-   Guru
-   Hari (Senin - Sabtu)
-   Waktu (contoh: 08:00)
-   Kelas (contoh: Kelompok A1)
-   Ruang
-   **Tanggal Mulai Semester** (contoh: 1 Januari 2025)

### 2. Sistem Auto-Generate 14 Pertemuan

Setelah jadwal disimpan, sistem otomatis:

**Step 1**: Generate 14 record pertemuan dengan tanggal spesifik

```php
// Contoh: Jadwal Matematika, Senin 08:00, mulai 1 Januari 2025
Pertemuan 1: J01-P01 - Senin, 6 Januari 2025
Pertemuan 2: J01-P02 - Senin, 13 Januari 2025
Pertemuan 3: J01-P03 - Senin, 20 Januari 2025
...
Pertemuan 14: J01-P14 - Senin, 6 April 2025
```

**Step 2**: Auto-assign semua siswa di kelas ke semua pertemuan

```php
// Ambil semua siswa di kelas Kelompok A1
$siswaList = Siswa::where('kelas', 'Kelompok A1')->get();

// Untuk setiap pertemuan (1-14)
foreach ($pertemuanList as $pertemuan) {
    // Buat record absensi untuk setiap siswa
    foreach ($siswaList as $siswa) {
        Absensi::create([
            'id_siswa' => $siswa->id_siswa,
            'id_pertemuan' => $pertemuan->id_pertemuan,
            'status_kehadiran' => 'belum_absen', // Default
        ]);
    }
}
```

**Hasil**: Jika ada 20 siswa di kelas, otomatis tercipta **20 × 14 = 280 record absensi** (semua status: `belum_absen`)

### 3. Guru Melakukan Absensi Mingguan

Guru cukup:

1. Pilih pertemuan (contoh: Pertemuan 3 - 20 Januari 2025)
2. Checklist siswa yang hadir/izin/sakit/alpha
3. Simpan

Tidak perlu manual pilih siswa atau input tanggal karena sudah otomatis ter-generate!

## Keuntungan Sistem Baru

### ✅ Untuk Admin

-   **Sekali input jadwal = 14 pertemuan otomatis**
-   Tidak perlu repot buat jadwal setiap minggu
-   Semester planning jadi lebih terstruktur

### ✅ Untuk Guru

-   **Tidak perlu pilih siswa manual** saat absensi (sudah otomatis terdaftar semua)
-   Cukup pilih pertemuan ke berapa
-   Lebih cepat dan efisien

### ✅ Untuk Sistem

-   Data lebih konsisten dan terstruktur
-   Mudah tracking pertemuan mana yang sudah/belum absensi
-   Mudah generate laporan per pertemuan

## Migrasi Data Lama

### Langkah-langkah

1. ✅ Buat tabel `pertemuan`
2. ✅ Tambah kolom `hari`, `kelas`, `tanggal_mulai` ke tabel `jadwal`
3. ✅ Ubah tabel `absensi`: drop kolom `id_jadwal` dan `tanggal`, tambah `id_pertemuan`
4. ✅ Update enum `status_kehadiran` tambah `belum_absen`

### Perintah Migration

```bash
# Jalankan migration
php artisan migrate

# Jika ada error, rollback
php artisan migrate:rollback --step=4

# Reset database (HATI-HATI: Hapus semua data)
php artisan migrate:fresh --seed
```

## Model Changes

### Model Jadwal

**Tambahan**:

-   Method `generatePertemuan()`: Generate 14 pertemuan otomatis
-   Method `assignSiswaToPertemuan()`: Assign semua siswa ke semua pertemuan
-   Relasi `pertemuan()`: HasMany ke Pertemuan

### Model Pertemuan (Baru)

**Relasi**:

-   `jadwal()`: BelongsTo Jadwal
-   `absensi()`: HasMany Absensi

**Accessor**:

-   `label`: Format "Pertemuan 3 - 20/01/2025"

### Model Absensi

**Perubahan**:

-   Relasi `jadwal()` → dihapus
-   Relasi `pertemuan()` → ditambah (BelongsTo)
-   Field `id_jadwal` → `id_pertemuan`
-   Field `tanggal` → dihapus (ambil dari pertemuan)

## Controller Changes

### JadwalController

**Method `store()`**:

```php
// 1. Simpan jadwal
$jadwal->save();

// 2. Auto-generate 14 pertemuan
$jadwal->generatePertemuan();

// 3. Auto-assign semua siswa (sudah di dalam generatePertemuan)
// Hasil: 14 pertemuan × N siswa = semua terdaftar
```

**Validation Tambahan**:

-   `hari`: required, in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu
-   `kelas`: required, string
-   `tanggal_mulai`: required, date

## View Changes

### `admin/jadwal/create.blade.php`

**Field Baru**:

-   Dropdown **Hari** (Senin - Sabtu)
-   Input **Kelas** (text)
-   Input **Tanggal Mulai Semester** (date picker)
-   Info text: "Sistem akan otomatis membuat 14 pertemuan mingguan"

### Perlu Dibuat Nanti

-   `admin/pertemuan/index.blade.php` - List 14 pertemuan per jadwal
-   `guru/absensi/pertemuan.blade.php` - Absensi per pertemuan
-   `guru/jadwal/pertemuan.blade.php` - Lihat pertemuan di jadwal saya

## Testing Checklist

### 1. Test Create Jadwal

```
1. Login sebagai Admin
2. Buat jadwal baru:
   - Guru: Budi Santoso
   - Mata Pelajaran: Matematika
   - Hari: Senin
   - Waktu: 08:00
   - Kelas: Kelompok A1
   - Ruang: Ruang 101
   - Tanggal Mulai: 2025-01-06 (Senin)
3. Submit
4. Cek database:
   - Tabel jadwal: 1 record baru
   - Tabel pertemuan: 14 record (J01-P01 s/d J01-P14)
   - Tabel absensi: 14 × jumlah_siswa_kelas_A1 records
```

### 2. Test Absensi

```
1. Login sebagai Guru
2. Pilih jadwal Matematika
3. Lihat list pertemuan (1-14)
4. Pilih pertemuan ke-3
5. Harusnya muncul SEMUA siswa kelas A1 dengan status "Belum Absen"
6. Checklist hadir/izin/sakit/alpha
7. Submit
```

### 3. Test Edge Cases

-   Jadwal mulai bukan hari yang dipilih (misal: mulai Rabu, tapi jadwal Senin)
-   Kelas tidak ada siswa
-   Update jadwal setelah pertemuan di-generate

## Rollback Plan

Jika sistem bermasalah:

```bash
# 1. Rollback migration
php artisan migrate:rollback --step=4

# 2. Restore backup database (jika ada)
mysql -u root -p simpelteratai < backup_sebelum_pertemuan.sql

# 3. Atau fresh migrate dengan seeder lama
php artisan migrate:fresh --seed
```

## Notes

-   Sistem ini mengasumsikan 1 semester = 14 minggu
-   Jika butuh lebih/kurang, ubah constant di `Jadwal::generatePertemuan()`
-   Libur/cuti belum di-handle, masih generate 14 minggu berturut-turut
