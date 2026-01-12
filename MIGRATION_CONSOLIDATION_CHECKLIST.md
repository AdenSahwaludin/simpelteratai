# ✅ MIGRATION CONSOLIDATION IMPLEMENTATION CHECKLIST

**Project**: TK Teratai Management System  
**Date Started**: 2026-01-10  
**Estimated Duration**: 3-4 hours  
**Risk Level**: Medium (with proper testing)

---

## 📋 PRE-IMPLEMENTATION (Day 0 - Preparation)

### Database Backup

-   [ ] Create MySQL dump: `mysqldump -u root -p tkt_db > backup_full_$(date +%Y%m%d).sql`
-   [ ] Create schema only: `mysqldump -u root -p tkt_db --no-data > backup_schema_$(date +%Y%m%d).sql`
-   [ ] Store backups in safe location (Google Drive/external disk)
-   [ ] Verify backup integrity: `mysql -u root -p tkt_db < backup_full_*.sql`

### Documentation

-   [ ] Document current migration status: `php artisan migrate:status > migration_status_before.txt`
-   [ ] Screenshot database schema
-   [ ] List all current tables: `php artisan db:show > db_tables_before.txt`

### Analysis

-   [ ] Review `MIGRATION_CLEANUP_STRATEGY.md`
-   [ ] Review `MIGRATION_IMPLEMENTATION_GUIDE.md`
-   [ ] Run helper script: `bash scripts/migration-cleanup.sh` → Choose "1) Analyze"
-   [ ] Document findings in `MIGRATION_CHANGES.md`

---

## 🔧 IMPLEMENTATION PHASE (Day 1-2)

### Step 1: Update CREATE Table Files (2-3 hours)

#### Standalone Tables (No Foreign Keys to Custom Tables)

**SECTION A: Standalone (1 of 3)**

-   [ ] **Update `0001_01_01_000004_create_orang_tua_table.php`**

    -   [ ] Change `id_orang_tua` varchar: 4 → 7
    -   [ ] Verify all columns present
    -   [ ] Check timestamps included
    -   [ ] Test: `php artisan migrate:fresh`

-   [ ] **Update `0001_01_01_000006_create_admin_table.php`**

    -   [ ] Verify structure matches production
    -   [ ] Check if needs updates
    -   [ ] Test with fresh migration

-   [ ] **Update `0001_01_01_000007_create_mata_pelajaran_table.php`**
    -   [ ] Change `id_mata_pelajaran` varchar: 3 → 6
    -   [ ] Verify all columns present
    -   [ ] Test: `php artisan migrate:fresh`

**SECTION B: Dependent Tables (2 of 3)**

-   [ ] **Update `0001_01_01_000005_create_guru_table.php`**

    -   Consolidates from: `2025_11_19_080516_add_nip_to_guru_table.php`
    -   [ ] Add `nip` column (string, nullable)
    -   [ ] Change `id_guru` varchar: 3 → 6
    -   [ ] Verify no FK to custom tables
    -   [ ] Test: `php artisan migrate:fresh`

-   [ ] **Update `0001_01_01_000012_create_pengumuman_table.php`**

    -   Consolidates from: `2025_11_19_073203_add_publikasi_to_pengumuman_table.php`
    -   [ ] Add `publikasi` column (boolean, default false)
    -   [ ] Change `id_pengumuman` varchar: 3 → 6
    -   [ ] Verify FK to admin table
    -   [ ] Test: `php artisan migrate:fresh`

-   [ ] **Update `0001_01_01_000008_create_jadwal_table.php`**

    -   Consolidates from:
        -   `2025_11_30_120818_add_hari_column_to_jadwal_table`
        -   `2025_12_07_125511_remove_waktu_column_from_jadwal_table`
        -   `2025_11_30_172208_drop_jadwal_harian_and_add_time_range_to_jadwal`
    -   [ ] Add `hari` column (enum)
    -   [ ] Add `waktu_mulai` column (time)
    -   [ ] Add `waktu_selesai` column (time)
    -   [ ] Remove `waktu` column (if exists)
    -   [ ] Change all ID varchar: 3 → 6, 4 → 7
    -   [ ] Update FK references to correct lengths
    -   [ ] Test: `php artisan migrate:fresh`
    -   [ ] Verify FK to guru, mata_pelajaran

-   [ ] **Update `0001_01_01_000014_create_siswa_table.php`**
    -   [ ] Change `id_siswa` varchar: 4 → 7
    -   [ ] Change `id_orang_tua` varchar: 4 → 7
    -   [ ] Verify all columns
    -   [ ] Test: `php artisan migrate:fresh`

**SECTION C: Complex Tables (3 of 3)**

-   [ ] **Update `0001_01_01_000015_create_absensi_table.php`**

    -   Consolidates from:
        -   `2025_11_30_122604_modify_absensi_table_for_pertemuan`
        -   `2025_11_30_122856_update_absensi_status_enum`
    -   [ ] Change FK from jadwal → pertemuan
    -   [ ] Update enum values: [hadir, sakit, izin, alfa]
    -   [ ] Change all varchar lengths
    -   [ ] Verify FK to siswa, pertemuan (NOT jadwal!)
    -   [ ] Test: `php artisan migrate:fresh`

-   [ ] **Update `0001_01_01_000017_create_perilaku_table.php`**

    -   Consolidates from:
        -   `2024_11_24_000001_add_assessment_columns_to_perilaku_table`
        -   `2024_11_24_000002_modify_id_perilaku_column`
    -   [ ] Add `sosial` column (enum: baik, cukup, kurang)
    -   [ ] Add `emosional` column (enum: baik, cukup, kurang)
    -   [ ] Add `disiplin` column (enum: baik, cukup, kurang)
    -   [ ] Change `id_perilaku` varchar: 3 → 6
    -   [ ] Update FK reference lengths
    -   [ ] Test: `php artisan migrate:fresh`

-   [ ] **Update `0001_01_01_000013_create_komentar_table.php`**

    -   Consolidates from:
        -   `2025_12_05_082950_add_laporan_and_parent_to_komentar_table`
        -   `2025_12_05_093200_make_id_orang_tua_nullable_in_komentar_table`
    -   [ ] Add `id_laporan_lengkap` FK (string, nullable)
    -   [ ] Add `id_guru` FK (string, nullable)
    -   [ ] Add `parent_id` FK (string, nullable) for nesting
    -   [ ] Make `id_orang_tua` nullable
    -   [ ] Change `id_komentar` varchar: 4 → 7
    -   [ ] Change FK reference lengths
    -   [ ] Test: `php artisan migrate:fresh`

-   [ ] **Update `0001_01_01_000016_create_laporan_perkembangan_table.php`**
    -   Consolidates from:
        -   `2025_11_20_120445_alter_id_laporan_column_to_laporan_perkembangan_table`
        -   `2025_11_18_100454_make_id_absensi_nullable_in_laporan_perkembangan_table`
    -   [ ] Make `id_absensi` nullable
    -   [ ] Update all varchar lengths
    -   [ ] Verify FK references
    -   [ ] Test: `php artisan migrate:fresh`

#### New Tables (KEEP UNCHANGED)

-   [ ] **Verify `2025_11_19_104504_create_jadwal_siswa_table.php`** (pivot table - no changes)
-   [ ] **Verify `2025_11_30_120110_create_pertemuan_table.php`** (new entity - no changes)
-   [ ] **Verify `2025_12_03_164432_create_laporan_lengkap_table.php`** (new entity - no changes)

---

### Step 2: Code Review & Syntax Check (30 minutes)

-   [ ] Review all updated CREATE files for:

    -   [ ] Consistent naming conventions
    -   [ ] Correct varchar lengths
    -   [ ] All FK references correct
    -   [ ] Proper enum values
    -   [ ] Nullable columns correct

-   [ ] Validate PHP syntax:
    ```bash
    bash scripts/migration-cleanup.sh
    # Choose "7) Validate PHP syntax"
    ```
    -   [ ] All files pass syntax check
    -   [ ] No errors reported

---

### Step 3: Local Testing (1 hour)

**Test 1: Fresh Migration**

```bash
# Run fresh migration (drops all tables)
php artisan migrate:fresh

# Check all tables created
php artisan db:show

# Verify tables:
php artisan db:table orang_tua
php artisan db:table guru
php artisan db:table jadwal
php artisan db:table siswa
php artisan db:table absensi
php artisan db:table komentar
php artisan db:table perilaku
php artisan db:table pengumuman
php artisan db:table laporan_lengkap
php artisan db:table laporan_perkembangan
php artisan db:table pertemuan
php artisan db:table jadwal_siswa
```

-   [ ] All tables created successfully
-   [ ] All columns present with correct types
-   [ ] All varchar lengths correct
-   [ ] No errors in output

**Test 2: Database Seeding**

```bash
php artisan db:seed
```

-   [ ] Seeding completes without errors
-   [ ] Data inserted correctly
-   [ ] No FK constraint violations
-   [ ] Verify data in tables

**Test 3: Application Functionality**

```bash
php artisan test
```

-   [ ] All tests pass
-   [ ] No errors or warnings
-   [ ] Check logs: `tail -f storage/logs/laravel.log`

**Test 4: Verify Data Integrity**

```bash
# Check FK relationships
php artisan db:show --table=jadwal
php artisan db:show --table=absensi
php artisan db:show --table=siswa
```

-   [ ] All FK relationships present
-   [ ] Cascade delete configured correctly
-   [ ] No orphaned data

---

### Step 4: Prepare for File Deletion (30 minutes)

**Backup Old Migration Files**

```bash
bash scripts/migration-cleanup.sh
# Choose "2) Backup current migrations"
```

-   [ ] Backup directory created
-   [ ] All 34 migration files backed up
-   [ ] Location: `backups/migrations_backup_YYYYMMDD_HHMMSS/`

**Review Files to Delete**

```bash
bash scripts/migration-cleanup.sh
# Choose "3) List files to be deleted"
```

-   [ ] 17 files identified for deletion
-   [ ] List reviewed and confirmed

**Dry Run**

```bash
bash scripts/migration-cleanup.sh
# Choose "4) Dry run - Show what would be deleted"
```

-   [ ] Output shows exactly which files will be deleted
-   [ ] No CREATE files in the list
-   [ ] All modification files included

---

## 🧹 CLEANUP PHASE (Day 2)

### Step 5: Delete Old Migration Files (20 minutes)

⚠️ **POINT OF NO RETURN - Proceed with caution!**

**Final Verification**

-   [ ] Backup confirmed working
-   [ ] All tests passing on local
-   [ ] All CREATE files updated
-   [ ] Code review completed

**Execute Deletion**

```bash
bash scripts/migration-cleanup.sh
# Choose "5) Delete modification files (CAREFUL!)"
# Confirm with: YES I UNDERSTAND
```

-   [ ] 17 files deleted
-   [ ] 17 files remain in migrations directory
-   [ ] Verify: `ls -1 database/migrations/*.php | wc -l` = 17

**Verify After Deletion**

```bash
# Test migration:fresh still works
php artisan migrate:fresh --seed

# Verify table count
php artisan db:show
```

-   [ ] migrate:fresh still works
-   [ ] All tables created
-   [ ] No errors
-   [ ] Seeds apply correctly

---

## 📝 DOCUMENTATION & COMMIT (1 hour)

### Step 6: Update Documentation

-   [ ] Update `DATABASE_SCHEMA.md` with final schema
-   [ ] Create `MIGRATION_CONSOLIDATION_COMPLETED.md` with:
    -   [ ] Summary of changes
    -   [ ] Before/after file count
    -   [ ] List of consolidated changes
    -   [ ] Testing results
    -   [ ] Deployment checklist

### Step 7: Generate Report

```bash
bash scripts/migration-cleanup.sh
# Choose "6) Generate consolidation report"
```

-   [ ] Report generated: `MIGRATION_CONSOLIDATION_REPORT_*.txt`
-   [ ] Review report
-   [ ] Save in documentation

### Step 8: Git Commit & Push

```bash
# Stage changes
git add database/migrations/*.php
git add docs/MIGRATION_CLEANUP_STRATEGY.md
git add docs/MIGRATION_IMPLEMENTATION_GUIDE.md
git add docs/DATABASE_SCHEMA.md
git status

# Commit
git commit -m "refactor: consolidate migrations into final schema definition

- Consolidate 17 modification migrations into CREATE table files
- Reduce migration files from 34 to 17 (50% reduction)
- Update all ID varchar lengths (+3 digits) in CREATE files
- Add missing columns (nip, hari, sosial, emosional, disiplin, publikasi)
- Restructure absensi relationships (jadwal → pertemuan)
- Expand komentar relationships (add laporan_lengkap, guru, parent FK)
- Make id_absensi nullable in laporan_perkembangan
- Update enum values in absensi status

Consolidation includes:
- 2024_11_24_000001/000002 assessment columns
- 2025_11_18/19/20 add/make nullable operations
- 2025_11_30 hari, pertemuan restructuring
- 2025_12_05/07 komentar/jadwal refinements
- 2026_01_09/10 varchar expansion

Tested:
- migrate:fresh ✓
- db:seed ✓
- All tables created ✓
- Foreign keys correct ✓
- Tests passing ✓"

# Push to main
git push origin main
```

-   [ ] Commit message descriptive
-   [ ] All files staged
-   [ ] Push successful
-   [ ] GitHub shows new commit

---

## 🧪 TESTING ON STAGING (Day 3)

### Step 9: Staging Environment Test

**Prepare Staging**

-   [ ] Pull latest code: `git pull origin main`
-   [ ] Run fresh migration: `php artisan migrate:fresh`
-   [ ] Seed database: `php artisan db:seed`

**Functional Testing**

-   [ ] Login: Admin, Guru, OrangTua
-   [ ] Create new records in each entity
-   [ ] Edit existing records
-   [ ] Delete records
-   [ ] List all entities with filters
-   [ ] Test relationships (FK cascades)

**Database Verification**

-   [ ] Run: `php artisan db:show`
-   [ ] Verify table structure
-   [ ] Check FK relationships
-   [ ] Verify data integrity

**Performance Testing**

-   [ ] Monitor query performance
-   [ ] Check query logs
-   [ ] Verify no N+1 queries
-   [ ] Test with seeded data (large dataset)

-   [ ] All tests pass
-   [ ] No performance degradation
-   [ ] Ready for production

---

## 🚀 PRODUCTION DEPLOYMENT (Day 4)

### Step 10: Production Deployment

**Pre-Deployment**

-   [ ] Final backup of production database
-   [ ] Notify users of maintenance window
-   [ ] Prepare rollback plan
-   [ ] Schedule deployment during low-traffic period

**Deployment Steps**

```bash
# 1. Pull latest code
git pull origin main

# 2. Run migrations (not fresh! preserve data)
php artisan migrate

# 3. Seed if new data needed
php artisan db:seed --class=YourSeeder

# 4. Clear caches
php artisan cache:clear
php artisan config:cache
```

-   [ ] Code pulled successfully
-   [ ] Migrations run without errors
-   [ ] Check logs: `tail -100 storage/logs/laravel.log`
-   [ ] Verify tables exist
-   [ ] Test critical features

**Post-Deployment**

-   [ ] Monitor application logs
-   [ ] Monitor error tracking
-   [ ] Test all user roles
-   [ ] Verify no downtime
-   [ ] Document any issues

-   [ ] Deployment successful
-   [ ] Users notified of completion
-   [ ] All systems operational

---

## ✅ POST-IMPLEMENTATION

### Step 11: Cleanup & Documentation

-   [ ] Remove temporary files
-   [ ] Archive backups properly
-   [ ] Update team documentation
-   [ ] Hold team knowledge transfer session
-   [ ] Update development setup guide

**Future Best Practices**

-   [ ] Create schema change template
-   [ ] Document new migration workflow
-   [ ] Setup pre-commit hook to validate syntax
-   [ ] Add to development onboarding checklist

---

## 📊 METRICS & VALIDATION

### Before Implementation

-   Total migration files: **34**
-   Create files: **14**
-   Modification files: **20**
-   Consistency: **Low** (17 different approaches)

### After Implementation

-   Total migration files: **17** ✅
-   Create files: **14** (consolidated)
-   New table files: **3** (pertemuan, jadwal_siswa, laporan_lengkap)
-   Modification files: **0** ✅
-   Consistency: **High** (single approach)

### Benefits Achieved

-   ✅ 50% reduction in migration files
-   ✅ Single source of truth for schema
-   ✅ Cleaner version control history
-   ✅ Easier migration:fresh workflow
-   ✅ Better for onboarding developers
-   ✅ Reduced merge conflicts

---

## 🚨 ROLLBACK PLAN

If critical issues occur:

```bash
# Option 1: Restore from backup
mysql -u root -p tkt_db < backup_full_$(date +%Y%m%d).sql

# Option 2: Revert code
git revert <commit-hash>
git push origin main

# Option 3: Restore from backup + revert code
mysql -u root -p tkt_db < backup_full_*.sql
git revert <commit-hash>
php artisan migrate
```

-   [ ] Rollback tested locally
-   [ ] Rollback procedure documented
-   [ ] Team briefed on rollback

---

## 📞 SUPPORT & TROUBLESHOOTING

**Common Issues & Solutions:**

1. **Foreign Key Constraint Error**

    - Check FK references match new varchar lengths
    - Ensure parent table created before child table

2. **Table Already Exists Error**

    - Run `php artisan migrate:reset` before fresh
    - Check for syntax errors in migration files

3. **Missing Column Error**

    - Verify column added to CREATE file
    - Check spelling and data type
    - Run migrate:fresh

4. **Data Loss**
    - Use backup: `mysql tkt_db < backup_full_*.sql`
    - Never use migrate:fresh on production with data!

**Escalation:**

-   [ ] Document any issues
-   [ ] Post in team chat
-   [ ] Create GitHub issue if bug found
-   [ ] Contact senior developer if stuck

---

## ✨ FINAL SIGN-OFF

-   [ ] All checklist items completed
-   [ ] Testing successful on staging & production
-   [ ] Documentation complete
-   [ ] Team trained on new workflow
-   [ ] Metrics show improvement
-   [ ] Ready to mark as DONE

**Completion Date**: ******\_\_\_\_******  
**Completed By**: ******\_\_\_\_******  
**Reviewed By**: ******\_\_\_\_******

---

**Status**: 🟢 READY TO IMPLEMENT  
**Risk Level**: 🟡 MEDIUM (mitigated with testing & backup)  
**Confidence**: 🟢 HIGH (with proper testing)

---

_Last Updated: 2026-01-10_  
_Next Review: After production deployment_
