# Solusi Duplicate ID Error pada Tabel Orang Tua

## Masalah

Terjadi error `Illuminate\Database\UniqueConstraintViolationException (SQLSTATE[23000])` saat menyimpan data ke tabel `orang_tua`. Hal ini terjadi karena:

1. **ID di-generate dengan format** `OT + count() + 1`
2. **Count() tidak akurat** setelah data dihapus - misalnya 5 data dihapus menjadi 4, tetapi ID yang dibuat adalah `OT005` (count + 1 = 4 + 1)
3. **Jika OT005 pernah ada sebelumnya**, sistem akan mencoba membuat ID yang sama, menyebabkan unique constraint violation

## Contoh Skenario Masalah

```
Status Awal:
OT001, OT002, OT003, OT004, OT005 (5 data)

Setelah Hapus OT003:
OT001, OT002, OT004, OT005 (4 data)
count() = 4

Insert Data Baru:
newID = 'OT' + str_pad(count() + 1, 3, '0')
     = 'OT' + str_pad(4 + 1, 3, '0')
     = 'OT005' ❌ DUPLICATE! OT005 sudah ada
```

## Solusi Implementasi

### 1. Buat Method `generateUniqueId()` di Model OrangTua

**File**: `app/Models/OrangTua.php`

```php
/**
 * Generate unique ID with format OT001, OT002, etc.
 * Safe from duplicate even when data is deleted.
 */
public static function generateUniqueId(): string
{
    $lastId = static::orderByRaw("CAST(SUBSTRING(id_orang_tua, 3) AS UNSIGNED) DESC")
        ->limit(1)
        ->pluck('id_orang_tua')
        ->first();

    $nextNumber = $lastId
        ? (int) substr($lastId, 2) + 1
        : 1;

    return 'OT' . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
}
```

### 2. Penjelasan Logika

```php
// 1. Mencari ID terakhir yang ada (bukan count)
$lastId = static::orderByRaw("CAST(SUBSTRING(id_orang_tua, 3) AS UNSIGNED) DESC")

// 2. Extract nomor dari OT005 → 005 → 5
$nextNumber = (int) substr($lastId, 2) + 1;  // 5 + 1 = 6

// 3. Format ulang: 6 → OT006
return 'OT' . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
```

**Keuntungan**:

-   ✅ Menggunakan MAX database, bukan count()
-   ✅ Aman dari duplicate meskipun ada data dihapus
-   ✅ Tetap urut dan sequential

### 3. Update Controller untuk Menggunakan Method

Ganti di 2 lokasi:

**File**: `app/Http/Controllers/Admin/OrangTuaController.php` (line 71)

```php
// SEBELUM (❌ TIDAK AMAN):
$orangTua->id_orang_tua = 'OT'.str_pad((string) (OrangTua::count() + 1), 3, '0', STR_PAD_LEFT);

// SESUDAH (✅ AMAN):
$orangTua->id_orang_tua = OrangTua::generateUniqueId();
```

**File**: `app/Http/Controllers/Admin/SiswaController.php` (line 221)

```php
// SEBELUM (❌ TIDAK AMAN):
$orangTua->id_orang_tua = 'OT'.str_pad((string) (OrangTua::count() + 1), 3, '0', STR_PAD_LEFT);

// SESUDAH (✅ AMAN):
$orangTua->id_orang_tua = OrangTua::generateUniqueId();
```

## Pengujian Manual

```bash
# Scenario Pengujian

# 1. Buat 5 data OrangTua
OT001, OT002, OT003, OT004, OT005

# 2. Hapus OT003
OT001, OT002, OT004, OT005

# 3. Buat data baru
nextID = OrangTua::generateUniqueId() // OT006 ✅ BUKAN OT003!
```

## Alternatif Solusi (Jika Ingin Lebih Fleksibel)

### Menggunakan UUID

Lebih unik dan tidak perlu khawatir tentang urutan:

```php
// Migration
$table->string('id_orang_tua', 36)->primary(); // UUID length

// Model
public static function generateUniqueId(): string
{
    return 'OT-' . Str::uuid();
}
```

**Pro**: Sangat unik, tidak ada kemungkinan collision
**Con**: ID menjadi panjang, kurang "friendly"

### Menggunakan Auto Increment Integer

Separating concern - internal ID (increment) vs display ID (OT-formatted):

```php
// Migration
$table->id(); // auto increment internal
$table->string('id_orang_tua')->unique(); // display ID: OT001, OT002, dst
```

## Kesimpulan

✅ **Solusi saat ini sudah diterapkan** di:

-   Model `OrangTua` dengan method `generateUniqueId()`
-   Controller `OrangTuaController`
-   Controller `SiswaController`

**Keuntungan Solusi Ini**:

-   Minim perubahan (hanya tambah 1 method + update 2 controller)
-   Tetap mempertahankan format ID yang sudah ada
-   Aman dari duplicate key error
-   Tidak perlu migration database

---

**Tanggal Implementasi**: 2026-01-10
**Status**: ✅ Selesai
