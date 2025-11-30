# Troubleshooting Guide - Fitur Input Nilai Massal

## Problem: Nilai tidak tersimpan ke database

### Diagnosis Steps

#### 1. Check Browser Console

Buka Developer Tools (F12) → Console tab

```
Cari error JavaScript seperti:
- Failed to fetch
- Network error
- Validation error
```

#### 2. Check Network Tab

Developer Tools → Network tab → Klik tombol "Simpan"

```
- Status Code: Harus 302 (redirect) atau 200 (success)
- Request Payload: Check apakah data nilai[] terkirim
- Response: Check response dari server
```

#### 3. Check Laravel Log

File: `storage/logs/laravel.log`

```bash
# Cari log entries seperti:
[timestamp] local.INFO: Bulk Store Request {"jadwal":"JD1","nilai_count":3,...}
[timestamp] local.INFO: Updating existing nilai for SW01 {...}
[timestamp] local.INFO: Creating new nilai for SW02 {...}
```

#### 4. Check Database Directly

```sql
-- Check existing records
SELECT * FROM laporan_perkembangan
WHERE id_mata_pelajaran = 'MP1'
ORDER BY id_siswa;

-- Check if updates are happening
SELECT id_laporan, id_siswa, nilai, updated_at
FROM laporan_perkembangan
WHERE id_mata_pelajaran = 'MP1'
ORDER BY updated_at DESC;
```

### Common Issues & Solutions

#### Issue 1: Form tidak submit

**Symptoms**: Klik tombol Simpan tidak ada response

**Solution**:

1. Check JavaScript console untuk error
2. Pastikan form ID `nilaiForm` ada
3. Pastikan CSRF token ada: `@csrf`
4. Check route name correct: `guru.input-nilai.bulk-store`

#### Issue 2: Nilai tidak berubah di database

**Symptoms**: Submit berhasil tapi nilai tetap sama

**Possible Causes**:

1. **Duplikasi input** - Desktop table dan mobile card sama-sama submit

    ```javascript
    // Check: Hanya 1 view yang aktif berdasarkan screen size
    // Desktop: class="hidden md:block"
    // Mobile: class="md:hidden"
    ```

2. **Cache browser** - Nilai lama di-cache

    ```
    Solution: Hard refresh (Ctrl+Shift+R)
    ```

3. **Transaction rollback** - Ada error di tengah proses
    ```php
    // Check log untuk error messages
    storage/logs/laravel.log
    ```

#### Issue 3: Hanya sebagian nilai tersimpan

**Symptoms**: Beberapa nilai update, sisanya tidak

**Check**:

1. Validasi nilai (0-100)
2. Siswa terdaftar di jadwal
3. Field nilai tidak kosong

**Debug**:

```php
// Check log untuk error messages per siswa:
"Nilai siswa (ID: SW01) tidak valid (harus 0-100)."
"Siswa (ID: SW02) tidak terdaftar untuk jadwal ini."
```

#### Issue 4: Duplicate key error

**Symptoms**: Error "Duplicate entry 'LP008'"

**Solution**:

```sql
-- Check max ID
SELECT id_laporan FROM laporan_perkembangan
ORDER BY CAST(SUBSTRING(id_laporan, 3) AS UNSIGNED) DESC
LIMIT 1;

-- Jika ada gap, adjust manually atau delete duplicate
```

### Testing Steps

#### Manual Test 1: UPDATE existing nilai

```
1. Login sebagai guru
2. Buka Input Nilai Massal
3. Pilih jadwal yang siswa-siswanya sudah punya nilai
4. Ubah nilai siswa (misal: 85 → 90)
5. Klik Simpan
6. Check: Nilai berubah di database dan list nilai
```

#### Manual Test 2: INSERT new nilai

```
1. Login sebagai guru
2. Buka Input Nilai Massal
3. Pilih jadwal yang siswa-siswanya belum punya nilai
4. Isi nilai baru (misal: 75)
5. Klik Simpan
6. Check: Record baru dibuat di database
```

#### Manual Test 3: Mix INSERT + UPDATE

```
1. Pilih jadwal dengan mix siswa (ada yang sudah punya nilai, ada yang belum)
2. Isi/ubah nilai untuk semua siswa
3. Klik Simpan
4. Check: Existing di-update, new di-insert
```

### Debug Commands

#### Check Controller Method

```bash
php artisan tinker

$guru = App\Models\Guru::first();
$jadwal = $guru->jadwal()->first();
$siswa = $jadwal->siswa()->first();

# Check if existing record
$existing = App\Models\LaporanPerkembangan::where('id_siswa', $siswa->id_siswa)
    ->where('id_mata_pelajaran', $jadwal->id_mata_pelajaran)
    ->first();

if ($existing) {
    echo "Existing: " . $existing->nilai;
    $existing->update(['nilai' => 99]);
    echo "Updated: " . $existing->fresh()->nilai;
} else {
    echo "No existing record";
}
```

#### Check Route

```bash
php artisan route:list --name=input-nilai

# Should show:
# POST  guru/input-nilai-bulk | guru.input-nilai.bulk-store
```

#### Clear Cache

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

### Performance Check

#### Check Query Count

Add to controller temporarily:

```php
\DB::enableQueryLog();
// ... bulk store logic ...
\Log::info('Queries', \DB::getQueryLog());
```

Expected queries per siswa:

-   1 query: Check existing
-   1 query: UPDATE or INSERT

Total: 2n queries (n = jumlah siswa)

### Verification Checklist

Before reporting bug:

-   [ ] Browser console: No JavaScript errors
-   [ ] Network tab: Request sent with nilai[] data
-   [ ] Laravel log: Controller method executed
-   [ ] Database: Records created/updated
-   [ ] Form: Correct input names `nilai[SW01]`
-   [ ] CSRF: Token present in form
-   [ ] Route: Correct route name and method (POST)

### Contact Support

If issue persists after all checks:

1. Attach Laravel log file
2. Attach screenshot of Network tab (Request/Response)
3. Attach screenshot of browser console
4. List steps to reproduce

---

## Success Indicators

✓ Form submit → Redirect ke list nilai
✓ Success message: "Berhasil menyimpan nilai untuk X siswa"
✓ Database: `updated_at` timestamp changed
✓ Log: "Bulk Store Request" entry
✓ Log: "Updated/Created nilai for..." entries
