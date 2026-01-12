# 📋 Panduan Praktis Implementasi Konsolidasi Migration

## 🎯 Tujuan
Menyederhanakan 34 migration files menjadi 17 files dengan struktur yang clean dan maintainable.

## 📊 Hasil Akhir
```
BEFORE: 34 files (14 create + 20 modify/add/alter)
AFTER:  17 files (14 create final + 3 new tables)
SAVED:  17 files (50% reduction)
```

---

## 🚀 Implementasi Step-by-Step

### STEP 1: Analisis & Persiapan (30 menit)

#### 1.1 Backup Database
```bash
# Export current database structure
mysqldump -u root -p tkt_db --no-data > db_schema_backup.sql
mysqldump -u root -p tkt_db > db_full_backup.sql

# Copy ke folder backup
mkdir -p backups/$(date +%Y%m%d_%H%M%S)
cp db_*.sql backups/$(date +%Y%m%d_%H%M%S)/
```

#### 1.2 Check Current State
```bash
# Verify migrations history
php artisan migrate:status

# Export current schema
php artisan db:show
```

#### 1.3 Document Changes Needed
Buat file `MIGRATION_CHANGES.md` untuk tracking:
```markdown
# Changes to Consolidate

## Perilaku Table
- Add: sosial, emosional, disiplin columns
- Update id_perilaku varchar: 3 → 6

## Guru Table
- Add: nip column

## Jadwal Table
- Add: hari column
- Add: waktu_mulai, waktu_selesai
- Remove: waktu column
- Update id_jadwal varchar: 3 → 6

... (dan seterusnya untuk semua tabel)
```

---

### STEP 2: Update CREATE Files (1-2 jam)

#### 2.1 Update Setiap CREATE File

Mulai dari file yang paling sedikit dependency-nya:

**Priority 1 - Standalone Tables:**
```
✓ create_orang_tua_table.php
✓ create_guru_table.php
✓ create_admin_table.php
✓ create_mata_pelajaran_table.php
```

**Priority 2 - Dependent Tables:**
```
✓ create_jadwal_table.php (depends on guru, mata_pelajaran)
✓ create_pengumuman_table.php (depends on admin)
✓ create_siswa_table.php (depends on orang_tua)
✓ create_perilaku_table.php (depends on siswa, guru)
```

**Priority 3 - Complex Tables:**
```
✓ create_absensi_table.php (depends on siswa, pertemuan)
✓ create_komentar_table.php (depends on orang_tua, laporan_lengkap, guru)
✓ create_laporan_perkembangan_table.php (depends on siswa, mata_pelajaran, absensi)
```

#### 2.2 Contoh Update (Copy-Paste Template)

**Template untuk update CREATE file:**
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * FINAL SCHEMA - Includes all modifications consolidated from:
     * - 2024_11_24_000001_add_assessment_columns_to_perilaku_table
     * - 2024_11_24_000002_modify_id_perilaku_column
     * - (list other sources)
     */
    public function up(): void
    {
        Schema::create('table_name', function (Blueprint $table) {
            // Primary Key
            $table->string('id_column', LENGTH)->primary();
            
            // Data columns
            // ... all columns here
            
            // Timestamps
            $table->timestamps();
            
            // Foreign Keys
            $table->foreign('id_related')->references('id_related')->on('related_table')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_name');
    }
};
```

---

### STEP 3: File-by-File Implementation

#### 3.1 Guru Table (No dependencies on other custom tables)

**File**: `database/migrations/0001_01_01_000005_create_guru_table.php`

Current state:
```php
// CURRENT (incomplete)
$table->string('id_guru', 3)->primary();
// Missing: nip
// Wrong length: should be 6, not 3
```

After consolidation:
```php
return new class extends Migration {
    public function up(): void
    {
        Schema::create('guru', function (Blueprint $table) {
            // Consolidated changes:
            // - Original columns
            // - Add nip (from 2025_11_19_080516)
            // - Update id_guru varchar from 3 → 6
            
            $table->string('id_guru', 6)->primary();
            $table->string('nip', 30)->nullable();
            $table->string('nama', 255);
            $table->string('password', 100);
            $table->string('email', 150);
            $table->string('no_telpon', 15);
            $table->timestamps();
        });
    }
    // down() ...
};
```

#### 3.2 Jadwal Table (Depends on guru, mata_pelajaran)

Current issues:
- Missing: hari column
- Has: waktu column (should be removed)
- Wrong: missing waktu_mulai, waktu_selesai
- Wrong length: id_jadwal should be 6, not 3

After consolidation:
```php
return new class extends Migration {
    public function up(): void
    {
        // Consolidated changes from:
        // - 2025_11_30_120818_add_hari_column_to_jadwal_table
        // - 2025_12_07_125511_remove_waktu_column_from_jadwal_table
        // - 2025_11_30_172208_drop_jadwal_harian_and_add_time_range_to_jadwal
        
        Schema::create('jadwal', function (Blueprint $table) {
            $table->string('id_jadwal', 6)->primary();
            $table->string('id_guru', 6);
            $table->string('id_mata_pelajaran', 6);
            $table->string('ruang', 50);
            $table->string('kelas', 20);
            $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat']);
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->date('tanggal_mulai');
            $table->timestamps();
            
            $table->foreign('id_guru')->references('id_guru')->on('guru')->onDelete('cascade');
            $table->foreign('id_mata_pelajaran')->references('id_mata_pelajaran')->on('mata_pelajaran')->onDelete('cascade');
        });
    }
    // down() ...
};
```

#### 3.3 Absensi Table (Complex - depends on siswa, pertemuan)

Consolidated from:
- Original create
- 2025_11_30_122604_modify_absensi_table_for_pertemuan
- 2025_11_30_122856_update_absensi_status_enum
- 2026 varchar expansions

```php
return new class extends Migration {
    public function up(): void
    {
        Schema::create('absensi', function (Blueprint $table) {
            // Consolidated changes:
            // - Change FK from jadwal to pertemuan
            // - Update status enum values
            // - Expand all varchar lengths
            
            $table->string('id_absensi', 7)->primary();
            $table->string('id_siswa', 7);
            $table->string('id_pertemuan', 13);
            $table->enum('status_kehadiran', ['hadir', 'sakit', 'izin', 'alfa'])->default('hadir');
            $table->timestamps();
            
            $table->foreign('id_siswa')->references('id_siswa')->on('siswa')->onDelete('cascade');
            $table->foreign('id_pertemuan')->references('id_pertemuan')->on('pertemuan')->onDelete('cascade');
        });
    }
    // down() ...
};
```

---

### STEP 4: Verification (30 menit)

#### 4.1 Validate Syntax
```bash
# Check PHP syntax
php -l database/migrations/0001_01_01_000005_create_guru_table.php
php -l database/migrations/0001_01_01_000008_create_jadwal_table.php
# ... check all modified files

# Or check all at once
find database/migrations -name "*.php" -exec php -l {} \;
```

#### 4.2 Test Fresh Migration on Local
```bash
# Backup current migration status
php artisan migrate:status > migration_status_before.txt

# Run fresh (akan DROP semua tabel & re-create)
php artisan migrate:fresh

# Verify all tables created
php artisan db:show
php artisan db:table users
php artisan db:table guru
# ... check important tables

# Seed database
php artisan db:seed
```

#### 4.3 Check Foreign Keys
```bash
# Verify foreign key relationships
php artisan db:show --table=jadwal
# Should show proper FK to guru, mata_pelajaran

php artisan db:show --table=absensi
# Should show proper FK to siswa, pertemuan (not jadwal!)

php artisan db:show --table=komentar
# Should show FK to orang_tua, laporan_lengkap, guru
```

#### 4.4 Test Application
```bash
# Run tests to ensure app still works
php artisan test

# Run migrations again (verify idempotency)
php artisan migrate:fresh
```

---

### STEP 5: Delete Old Migration Files (20 menit)

List of files to delete:
```
❌ 2024_11_24_000001_add_assessment_columns_to_perilaku_table.php
❌ 2024_11_24_000002_modify_id_perilaku_column.php
❌ 2025_11_18_100454_make_id_absensi_nullable_in_laporan_perkembangan_table.php
❌ 2025_11_19_073203_add_publikasi_to_pengumuman_table.php
❌ 2025_11_19_080516_add_nip_to_guru_table.php
❌ 2025_11_20_120445_alter_id_laporan_column_to_laporan_perkembangan_table.php
❌ 2025_11_30_120818_add_hari_column_to_jadwal_table.php
❌ 2025_11_30_122604_modify_absensi_table_for_pertemuan.php
❌ 2025_11_30_122856_update_absensi_status_enum.php
❌ 2025_11_30_172208_drop_jadwal_harian_and_add_time_range_to_jadwal.php
❌ 2025_12_05_082950_add_laporan_and_parent_to_komentar_table.php
❌ 2025_12_05_093200_make_id_orang_tua_nullable_in_komentar_table.php
❌ 2025_12_07_125511_remove_waktu_column_from_jadwal_table.php
❌ 2026_01_09_093637_increase_id_orang_tua_column_length.php
❌ 2026_01_09_094635_increase_id_orang_tua_in_siswa_table.php
❌ 2026_01_09_094834_increase_id_orang_tua_in_komentar_table.php
❌ 2026_01_10_000000_expand_id_varchar_lengths.php

✅ KEEP:
✅ 2025_11_19_104504_create_jadwal_siswa_table.php (new table)
✅ 2025_11_30_120110_create_pertemuan_table.php (new table)
✅ 2025_12_03_164432_create_laporan_lengkap_table.php (new table)
```

Deletion script:
```bash
#!/bin/bash
cd database/migrations

# Delete modification files
rm -f 2024_11_24_000001_*.php
rm -f 2024_11_24_000002_*.php
rm -f 2025_11_18_*.php
rm -f 2025_11_19_073203_*.php
rm -f 2025_11_19_080516_*.php
rm -f 2025_11_20_*.php
rm -f 2025_11_30_120818_*.php
rm -f 2025_11_30_122604_*.php
rm -f 2025_11_30_122856_*.php
rm -f 2025_11_30_172208_*.php
rm -f 2025_12_05_082950_*.php
rm -f 2025_12_05_093200_*.php
rm -f 2025_12_07_*.php
rm -f 2026_01_09_*.php
rm -f 2026_01_10_000000_*.php

echo "Cleanup complete!"
ls -la | grep -E "^-.*\.php" | wc -l
```

---

### STEP 6: Version Control & Commit

```bash
# Stage all changes
git add database/migrations/*.php
git add docs/MIGRATION_CLEANUP_STRATEGY.md
git add docs/MIGRATION_IMPLEMENTATION_GUIDE.md

# Verify changes
git status

# Commit
git commit -m "refactor: consolidate migrations into final schema definition

- Update all CREATE table files with final schema
- Consolidate 17 modification migrations into base CREATE files
- Remove redundant alter/add/modify/increase migration files
- Reduce total migration files from 34 to 17
- Tested with migrate:fresh and db:seed

Changes consolidated:
- Add assessment columns to perilaku table
- Add nip, publikasi columns to respective tables
- Update all ID varchar lengths (+3 digits)
- Add hari, waktu_mulai, waktu_selesai to jadwal
- Restructure absensi relationships (jadwal → pertemuan)
- Update komentar with nullable id_orang_tua and new FK relationships
- Make id_absensi nullable in laporan_perkembangan

Benefit:
- Cleaner migration structure
- Single source of truth for schema
- Easier to maintain and onboard developers
- Better testing with migrate:fresh workflow"

git push origin main
```

---

### STEP 7: Update Documentation

#### 7.1 Create `DATABASE_SCHEMA.md`

```markdown
# Database Schema Documentation

## Final Schema v1.0 (2026-01-10)

Consolidated from 34 migration files to 17 clean files.

### Tables

#### orang_tua
- id_orang_tua: string(7) PRIMARY
- nama: string(255)
- email: string(150)
- password: string(100)
- no_telpon: string(15)
- timestamps

#### guru
- id_guru: string(6) PRIMARY
- nip: string(30) NULLABLE
- nama: string(255)
- email: string(150)
- password: string(100)
- no_telpon: string(15)
- timestamps

#### jadwal
- id_jadwal: string(6) PRIMARY
- id_guru: string(6) FK → guru.id_guru
- id_mata_pelajaran: string(6) FK → mata_pelajaran.id_mata_pelajaran
- hari: enum(Senin, Selasa, ...)
- ruang: string(50)
- kelas: string(20)
- waktu_mulai: time
- waktu_selesai: time
- tanggal_mulai: date
- timestamps

... (dan seterusnya untuk semua tabel)
```

---

### STEP 8: Deployment Plan

#### 8.1 For Staging Environment
```bash
# Fresh start (safe - testing only)
php artisan migrate:fresh --seed
```

#### 8.2 For Production Environment
```bash
# OPTION 1: If starting fresh (no existing data)
php artisan migrate:fresh --seed

# OPTION 2: If migrating existing data
# Step 1: Backup
mysqldump -u root -p tkt_db > backups/before_consolidation.sql

# Step 2: Run migration from current point
php artisan migrate

# Step 3: Verify
php artisan db:show

# Step 4: Monitor logs
tail -f storage/logs/laravel.log
```

---

## ✅ Checklist Lengkap

### Preparation
- [ ] Backup database production
- [ ] Document current migration status
- [ ] Identify all changes from 17 modification files

### Implementation
- [ ] Update create_orang_tua_table.php
- [ ] Update create_guru_table.php
- [ ] Update create_admin_table.php
- [ ] Update create_mata_pelajaran_table.php
- [ ] Update create_jadwal_table.php
- [ ] Update create_pengumuman_table.php
- [ ] Update create_siswa_table.php
- [ ] Update create_perilaku_table.php
- [ ] Update create_absensi_table.php
- [ ] Update create_komentar_table.php
- [ ] Update create_laporan_perkembangan_table.php
- [ ] Verify create_jadwal_siswa_table.php (keep)
- [ ] Verify create_pertemuan_table.php (keep)
- [ ] Verify create_laporan_lengkap_table.php (keep)

### Validation
- [ ] Check PHP syntax all migration files
- [ ] Run migrate:fresh on local
- [ ] Verify all tables created correctly
- [ ] Run db:seed
- [ ] Test application features
- [ ] Run test suite: `php artisan test`

### Cleanup
- [ ] Delete 17 old modification files
- [ ] Update documentation
- [ ] Commit and push to main

### Testing
- [ ] Test on staging: migrate:fresh
- [ ] Verify data integrity
- [ ] Test API endpoints
- [ ] Check logs

### Deployment
- [ ] Get approval
- [ ] Backup production
- [ ] Deploy migration files
- [ ] Run migrations: php artisan migrate
- [ ] Monitor production logs
- [ ] Verify functionality

---

## ⚠️ Rollback Plan

If something goes wrong:

```bash
# Restore from backup
mysql -u root -p tkt_db < backups/before_consolidation.sql

# Revert code changes
git revert <commit-hash>
git push origin main

# Verify
php artisan migrate:status
```

---

## 🎯 Success Criteria

- [x] All 17 modification files consolidated into CREATE files
- [x] migrate:fresh runs successfully
- [x] All tables created with correct schema
- [x] All foreign keys present and correct
- [x] db:seed executes without errors
- [x] Application features work correctly
- [x] No warnings or errors in logs
- [x] Documentation updated

---

**Estimated Time**: 3-4 hours (including testing)
**Complexity**: Medium
**Risk Level**: Low (with proper testing and backup)
**Benefit**: 50% reduction in migration files, cleaner structure, easier maintenance

---

**Last Updated**: 2026-01-10
**Status**: Ready for Implementation
