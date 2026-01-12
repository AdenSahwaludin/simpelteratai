# 🗂️ Strategi Pembersihan & Konsolidasi Migration Laravel

## 📊 Analisis Situasi Saat Ini

### Status Migration
- **Total Migration**: 34 files
- **Create Tables**: 15 files (baseline)
- **Modify/Add/Update**: 19 files (technical debt)
- **Problem**: Struktur terlalu kompleks, sulit untuk maintain

### Daftar Migration yang Perlu Dikonsolidasikan

**Baseline (Keep):**
```
✓ 0001_01_01_000000_create_users_table.php
✓ 0001_01_01_000001_create_cache_table.php
✓ 0001_01_01_000002_create_jobs_table.php
✓ 0001_01_01_000004_create_orang_tua_table.php
✓ 0001_01_01_000005_create_guru_table.php
✓ 0001_01_01_000006_create_admin_table.php
✓ 0001_01_01_000007_create_mata_pelajaran_table.php
✓ 0001_01_01_000008_create_jadwal_table.php
✓ 0001_01_01_000012_create_pengumuman_table.php
✓ 0001_01_01_000013_create_komentar_table.php
✓ 0001_01_01_000014_create_siswa_table.php
✓ 0001_01_01_000015_create_absensi_table.php
✓ 0001_01_01_000016_create_laporan_perkembangan_table.php
✓ 0001_01_01_000017_create_perilaku_table.php
```

**Modify/Add/Drop (Consolidate into Create):**
```
✗ 2024_11_24_000001_add_assessment_columns_to_perilaku_table.php
✗ 2024_11_24_000002_modify_id_perilaku_column.php
✗ 2025_11_18_100454_make_id_absensi_nullable_in_laporan_perkembangan_table.php
✗ 2025_11_19_073203_add_publikasi_to_pengumuman_table.php
✗ 2025_11_19_080516_add_nip_to_guru_table.php
✗ 2025_11_19_104504_create_jadwal_siswa_table.php → KEEP (new table)
✗ 2025_11_20_120445_alter_id_laporan_column_to_laporan_perkembangan_table.php
✗ 2025_11_30_120110_create_pertemuan_table.php → KEEP (new table)
✗ 2025_11_30_120818_add_hari_column_to_jadwal_table.php
✗ 2025_11_30_122604_modify_absensi_table_for_pertemuan.php
✗ 2025_11_30_122856_update_absensi_status_enum.php
✗ 2025_11_30_172208_drop_jadwal_harian_and_add_time_range_to_jadwal.php
✗ 2025_12_03_164432_create_laporan_lengkap_table.php → KEEP (new table)
✗ 2025_12_05_082950_add_laporan_and_parent_to_komentar_table.php
✗ 2025_12_05_093200_make_id_orang_tua_nullable_in_komentar_table.php
✗ 2025_12_07_125511_remove_waktu_column_from_jadwal_table.php
✗ 2026_01_09_093637_increase_id_orang_tua_column_length.php
✗ 2026_01_09_094635_increase_id_orang_tua_in_siswa_table.php
✗ 2026_01_09_094834_increase_id_orang_tua_in_komentar_table.php
✗ 2026_01_10_000000_expand_id_varchar_lengths.php → CONSOLIDATE
```

---

## 🎯 Strategi Pembersihan (Recommended Approach)

### Phase 1: Analisis Perubahan (Current)
✅ Inventory semua changes dari 19 modification files

### Phase 2: Konsolidasi (2-3 hari)
1. Update semua CREATE files dengan final schema
2. Hapus semua modification files
3. Test dengan `migrate:fresh`

### Phase 3: Backup & Deployment
1. Backup database production
2. Test di staging dengan migrate:fresh + seed
3. Deploy ke production dengan caution

---

## 🔧 Perubahan yang Perlu Dikonsolidasikan

### 1. **Tabel PERILAKU**
```php
// Add: assessment_columns (sosial, emosional, disiplin)
// Modify: id_perilaku format
// Final: Sudah ada di create file, cukup verifikasi
```

### 2. **Tabel LAPORAN_PERKEMBANGAN**
```php
// Make nullable: id_absensi
// Alter: id_laporan column
// Final: Update create file
```

### 3. **Tabel PENGUMUMAN**
```php
// Add: publikasi column (boolean)
// Final: Sudah ada atau update create file
```

### 4. **Tabel GURU**
```php
// Add: nip column (string)
// Final: Consolidate ke create_guru_table.php
```

### 5. **Tabel JADWAL**
```php
// Add: hari column
// Remove: waktu column
// Add: waktu_mulai, waktu_selesai (time range)
// Final: Konsolidasikan ke create_jadwal_table.php
```

### 6. **Tabel ABSENSI**
```php
// Modify: relationships (jadwal → pertemuan)
// Update: enum values
// Final: Update create_absensi_table.php
```

### 7. **Tabel KOMENTAR**
```php
// Add: laporan, parent columns
// Make nullable: id_orang_tua
// Final: Consolidate ke create_komentar_table.php
```

### 8. **Semua ID Columns**
```php
// Increase varchar lengths: +3 digits
// From: 4→7, 3→6, 10→13
// Final: Update all CREATE files dengan final lengths
```

### New Tables (KEEP)
```php
✓ jadwal_siswa (pivot table)
✓ pertemuan (new entity)
✓ laporan_lengkap (new entity)
```

---

## 📝 Implementasi Step-by-Step

### Step 1: Update CREATE Files dengan Final Schema

#### A. Create ORANG_TUA Table (Final)
```php
// File: 0001_01_01_000004_create_orang_tua_table.php
Schema::create('orang_tua', function (Blueprint $table) {
    $table->string('id_orang_tua', 7)->primary();  // ← UPDATED: 4→7
    $table->string('nama', 255);
    $table->string('password', 100);
    $table->string('email', 150);
    $table->string('no_telpon', 15);
    $table->timestamps();
});
```

#### B. Create SISWA Table (Final)
```php
// File: 0001_01_01_000014_create_siswa_table.php
Schema::create('siswa', function (Blueprint $table) {
    $table->string('id_siswa', 7)->primary();  // ← UPDATED: 4→7
    $table->string('nama', 255);
    $table->enum('jenis_kelamin', ['L', 'P']);
    $table->string('tempat_lahir', 50)->nullable();
    $table->date('tanggal_lahir');
    $table->string('kelas', 20);
    $table->string('alamat', 100);
    $table->string('id_orang_tua', 7);  // ← UPDATED: 4→7
    $table->timestamps();
    
    $table->foreign('id_orang_tua')->references('id_orang_tua')->on('orang_tua')->onDelete('cascade');
});
```

#### C. Create GURU Table (Final)
```php
// File: 0001_01_01_000005_create_guru_table.php
Schema::create('guru', function (Blueprint $table) {
    $table->string('id_guru', 6)->primary();  // ← UPDATED: 3→6
    $table->string('nip', 30)->nullable();    // ← ADDED
    $table->string('nama', 255);
    $table->string('password', 100);
    $table->string('email', 150);
    $table->string('no_telpon', 15);
    $table->timestamps();
});
```

#### D. Create JADWAL Table (Final)
```php
// File: 0001_01_01_000008_create_jadwal_table.php
Schema::create('jadwal', function (Blueprint $table) {
    $table->string('id_jadwal', 6)->primary();  // ← UPDATED: 3→6
    $table->string('id_guru', 6);              // ← UPDATED: 3→6
    $table->string('id_mata_pelajaran', 6);    // ← UPDATED: 3→6
    $table->string('ruang', 50);
    $table->string('kelas', 20);
    $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat']);  // ← ADDED
    $table->time('waktu_mulai');               // ← ADDED (replaced waktu)
    $table->time('waktu_selesai');             // ← ADDED
    $table->date('tanggal_mulai');
    $table->timestamps();
    
    $table->foreign('id_guru')->references('id_guru')->on('guru')->onDelete('cascade');
    $table->foreign('id_mata_pelajaran')->references('id_mata_pelajaran')->on('mata_pelajaran')->onDelete('cascade');
});
```

#### E. Create PENGUMUMAN Table (Final)
```php
// File: 0001_01_01_000012_create_pengumuman_table.php
Schema::create('pengumuman', function (Blueprint $table) {
    $table->string('id_pengumuman', 6)->primary();  // ← UPDATED: 3→6
    $table->string('judul', 255);
    $table->longText('isi');
    $table->date('tanggal');
    $table->boolean('publikasi')->default(false);  // ← ADDED
    $table->string('id_admin', 36)->nullable();
    $table->timestamps();
});
```

#### F. Create ABSENSI Table (Final)
```php
// File: 0001_01_01_000015_create_absensi_table.php
Schema::create('absensi', function (Blueprint $table) {
    $table->string('id_absensi', 7)->primary();     // ← UPDATED: 4→7
    $table->string('id_siswa', 7);                  // ← UPDATED: 4→7
    $table->string('id_pertemuan', 13);             // ← NEW KEY
    $table->enum('status_kehadiran', ['hadir', 'sakit', 'izin', 'alfa'])->default('hadir');  // ← UPDATED ENUM
    $table->timestamps();
    
    $table->foreign('id_siswa')->references('id_siswa')->on('siswa')->onDelete('cascade');
    $table->foreign('id_pertemuan')->references('id_pertemuan')->on('pertemuan')->onDelete('cascade');
});
```

#### G. Create KOMENTAR Table (Final)
```php
// File: 0001_01_01_000013_create_komentar_table.php
Schema::create('komentar', function (Blueprint $table) {
    $table->string('id_komentar', 7)->primary();        // ← UPDATED: 4→7
    $table->string('id_orang_tua', 7)->nullable();      // ← UPDATED: 4→7, MADE NULLABLE
    $table->string('id_laporan_lengkap', 13)->nullable();  // ← ADDED
    $table->string('id_guru', 6)->nullable();           // ← ADDED
    $table->string('parent_id', 7)->nullable();         // ← ADDED (for nesting)
    $table->longText('komentar');
    $table->timestamps();
    
    $table->foreign('id_orang_tua')->references('id_orang_tua')->on('orang_tua')->onDelete('cascade');
    $table->foreign('id_laporan_lengkap')->references('id_laporan_lengkap')->on('laporan_lengkap')->onDelete('cascade');
    $table->foreign('id_guru')->references('id_guru')->on('guru')->onDelete('cascade');
    $table->foreign('parent_id')->references('id_komentar')->on('komentar')->onDelete('cascade');
});
```

#### H. Create LAPORAN_PERKEMBANGAN Table (Final)
```php
// File: 0001_01_01_000016_create_laporan_perkembangan_table.php
Schema::create('laporan_perkembangan', function (Blueprint $table) {
    $table->string('id_laporan', 6)->primary();  // ← Check current name/length
    $table->string('id_siswa', 7);               // ← UPDATED: 4→7
    $table->string('id_mata_pelajaran', 6);      // ← UPDATED: 3→6
    $table->string('id_absensi', 7)->nullable(); // ← UPDATED: 4→7, MADE NULLABLE
    $table->integer('nilai')->nullable();
    $table->string('catatan_guru', 255)->nullable();
    $table->timestamps();
    
    $table->foreign('id_siswa')->references('id_siswa')->on('siswa')->onDelete('cascade');
    $table->foreign('id_mata_pelajaran')->references('id_mata_pelajaran')->on('mata_pelajaran')->onDelete('cascade');
    $table->foreign('id_absensi')->references('id_absensi')->on('absensi')->onDelete('set null');
});
```

#### I. Create PERILAKU Table (Final)
```php
// File: 0001_01_01_000017_create_perilaku_table.php
Schema::create('perilaku', function (Blueprint $table) {
    $table->string('id_perilaku', 6)->primary();  // ← UPDATED: 3→6
    $table->string('id_siswa', 7);                // ← UPDATED: 4→7
    $table->string('id_guru', 6);                 // ← UPDATED: 3→6
    $table->date('tanggal');
    $table->enum('sosial', ['baik', 'cukup', 'kurang'])->default('cukup');      // ← ADDED
    $table->enum('emosional', ['baik', 'cukup', 'kurang'])->default('cukup');   // ← ADDED
    $table->enum('disiplin', ['baik', 'cukup', 'kurang'])->default('cukup');    // ← ADDED
    $table->longText('catatan_perilaku')->nullable();
    $table->string('file_lampiran', 255)->nullable();
    $table->timestamps();
    
    $table->foreign('id_siswa')->references('id_siswa')->on('siswa')->onDelete('cascade');
    $table->foreign('id_guru')->references('id_guru')->on('guru')->onDelete('cascade');
});
```

### Step 2: Delete Old Modification Migration Files

```bash
# Hapus file-file yang sudah dikonsolidasikan:
rm database/migrations/2024_11_24_000001_add_assessment_columns_to_perilaku_table.php
rm database/migrations/2024_11_24_000002_modify_id_perilaku_column.php
rm database/migrations/2025_11_18_100454_make_id_absensi_nullable_in_laporan_perkembangan_table.php
rm database/migrations/2025_11_19_073203_add_publikasi_to_pengumuman_table.php
rm database/migrations/2025_11_19_080516_add_nip_to_guru_table.php
rm database/migrations/2025_11_20_120445_alter_id_laporan_column_to_laporan_perkembangan_table.php
rm database/migrations/2025_11_30_120818_add_hari_column_to_jadwal_table.php
rm database/migrations/2025_11_30_122604_modify_absensi_table_for_pertemuan.php
rm database/migrations/2025_11_30_122856_update_absensi_status_enum.php
rm database/migrations/2025_11_30_172208_drop_jadwal_harian_and_add_time_range_to_jadwal.php
rm database/migrations/2025_12_05_082950_add_laporan_and_parent_to_komentar_table.php
rm database/migrations/2025_12_05_093200_make_id_orang_tua_nullable_in_komentar_table.php
rm database/migrations/2025_12_07_125511_remove_waktu_column_from_jadwal_table.php
rm database/migrations/2026_01_09_093637_increase_id_orang_tua_column_length.php
rm database/migrations/2026_01_09_094635_increase_id_orang_tua_in_siswa_table.php
rm database/migrations/2026_01_09_094834_increase_id_orang_tua_in_komentar_table.php
rm database/migrations/2026_01_10_000000_expand_id_varchar_lengths.php

# KEEP files (new tables):
# - 2025_11_19_104504_create_jadwal_siswa_table.php
# - 2025_11_30_120110_create_pertemuan_table.php
# - 2025_12_03_164432_create_laporan_lengkap_table.php
```

### Step 3: Test Migration Fresh

```bash
# Test dengan fresh migration
php artisan migrate:fresh

# Verify schema
php artisan db:show

# Seed database
php artisan db:seed
```

---

## ✅ Checklist Implementasi

### Pre-Migration
- [ ] Backup database production
- [ ] Extract semua changes dari modification files
- [ ] Document current state
- [ ] Test pada staging environment

### Migration Update
- [ ] Update create_orang_tua_table.php
- [ ] Update create_siswa_table.php
- [ ] Update create_guru_table.php
- [ ] Update create_jadwal_table.php
- [ ] Update create_pengumuman_table.php
- [ ] Update create_absensi_table.php
- [ ] Update create_komentar_table.php
- [ ] Update create_laporan_perkembangan_table.php
- [ ] Update create_perilaku_table.php
- [ ] Update create_mata_pelajaran_table.php
- [ ] Verify create_jadwal_siswa_table.php (keep)
- [ ] Verify create_pertemuan_table.php (keep)
- [ ] Verify create_laporan_lengkap_table.php (keep)

### Cleanup
- [ ] Delete 17 modification migration files
- [ ] Commit: "refactor: consolidate migrations to single-source definition"

### Testing
- [ ] Run `migrate:fresh` on local
- [ ] Verify all tables created
- [ ] Run `db:seed` to verify seeders
- [ ] Check data integrity
- [ ] Test app functionality

### Deployment
- [ ] Test on staging: `migrate:fresh + seed`
- [ ] Production deployment with backup plan
- [ ] Monitor logs for errors

---

## 🎯 Best Practices Going Forward

### 1. **Update CREATE Files Langsung**
```php
// JANGAN bikin migration alter/add
// Kalau ada schema change:
// 1. Update create_*.php file
// 2. Test dengan migrate:fresh
// 3. Dokumentasi di file

// ONLY migrate:fresh di development
// Production: gunakan targeted migrations
```

### 2. **Manage Seeding**
```php
// database/seeders/DatabaseSeeder.php
php artisan db:seed --class=DatabaseSeeder
```

### 3. **Version Control untuk Schema**
```
✓ Create files selalu up-to-date dengan actual schema
✓ Modification files di-eliminate
✓ Clear, single source of truth
```

### 4. **Data Migrations (Separate)**
```php
// Jika ada data yang perlu ditransform:
// Gunakan dedicated migration SETELAH schema final

// Contoh:
// 0001_01_01_000014_create_siswa_table.php (structure)
// 2026_01_15_000000_migrate_siswa_data.php (data transformation)
```

### 5. **Development Workflow**
```bash
# Local development
php artisan migrate:fresh --seed
# Lalu test feature

# Staging/Production (dengan data)
php artisan migrate
# Hanya apply new migrations
```

---

## 📚 Referensi

**Laravel Migration Best Practices:**
- https://laravel.com/docs/11.x/migrations
- Schema: Definisikan lengkap di create file
- Avoid: Banyak modify/alter files
- Prefer: Clean structure, fresh start saat development

---

## 🔍 Notes

1. **Migration:Fresh** = `DROP ALL TABLES` + `RUN ALL MIGRATIONS`
   - Hanya untuk development/testing
   - JANGAN di production tanpa backup

2. **Order matters** untuk foreign keys:
   - Parent tables harus dibuat sebelum child
   - Current order sudah benar (check timestamps)

3. **Seeding** harus robust:
   - Factories untuk test data
   - Seeders untuk baseline data
   - Idempotent (aman run berkali-kali)

---

**Last Updated**: 2026-01-10
**Status**: Recommended Approach Ready for Implementation
