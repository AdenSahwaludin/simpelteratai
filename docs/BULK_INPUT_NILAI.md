# Dokumentasi Fitur Input Nilai Massal

## Overview

Fitur **Input Nilai Massal** memungkinkan guru untuk memasukkan nilai untuk seluruh siswa dalam satu jadwal sekaligus, dengan dukungan otomatis untuk UPDATE (jika nilai sudah ada) dan INSERT (jika nilai baru).

## Alur Kerja

### 1. Akses Fitur

-   Guru membuka menu **Input Nilai** → **Input Nilai Massal**
-   Atau: Dashboard → Aksi Cepat → **Input Nilai**

### 2. Memilih Jadwal

-   Guru memilih jadwal pelajaran dari dropdown
-   Sistem otomatis memuat daftar semua siswa yang terdaftar untuk jadwal tersebut
-   Sistem menampilkan nilai dan komentar yang sudah ada sebelumnya (jika ada)

### 3. Mengisi Nilai

-   Guru mengisi nilai (0-100) untuk siswa-siswa dalam tabel atau card view
-   Guru dapat mengisi komentar untuk setiap siswa (opsional)
-   Tombol **Isi Semua** dan **Hapus Semua** tersedia untuk kemudahan

### 4. Menyimpan

-   Guru klik tombol **Simpan Semua Nilai**
-   Sistem melakukan validasi:
    -   Cek format nilai (0-100)
    -   Cek siswa terdaftar di jadwal
    -   Skip nilai yang kosong

### 5. Proses Server

Sistem melakukan:

-   **UPDATE**: Jika nilai sudah ada untuk siswa tersebut
-   **INSERT**: Jika nilai baru (belum ada di database)
-   Semua operasi dilakukan dalam satu batch

## Arsitektur

### Routes

```php
Route::get('/input-nilai-bulk', 'bulkIndex')->name('input-nilai.bulk');
Route::get('/kelola-nilai-load-siswa', 'loadSiswaByJadwal')->name('input-nilai.load-siswa');
Route::post('/input-nilai-bulk', 'bulkStore')->name('input-nilai.bulk-store');
```

### Controller Methods

#### `bulkIndex()`

-   Menampilkan form input nilai massal
-   Pass jadwal list ke view

#### `loadSiswaByJadwal()` (AJAX)

-   Request: `GET /guru/kelola-nilai-load-siswa?id_jadwal=JD1`
-   Response JSON:
    ```json
    {
        "siswa": [
            { "id_siswa": "SW01", "nama": "Adi", "kelas": "A" },
            { "id_siswa": "SW02", "nama": "Budi", "kelas": "A" }
        ],
        "existingNilai": {
            "SW01": { "nilai": 85, "komentar": "Bagus", "id_laporan": "LP1" },
            "SW02": { "nilai": 90, "komentar": "Baik", "id_laporan": "LP006" }
        },
        "mata_pelajaran": "Matematika"
    }
    ```

#### `bulkStore(Request $request)`

-   Request Body:

    ```
    id_jadwal=JD1
    nilai[SW01]=85
    nilai[SW02]=90
    nilai[SW03]=95
    komentar[SW01]=Bagus
    komentar[SW02]=Baik
    komentar[SW03]=Excellent
    ```

-   Logika:
    1. Validasi jadwal milik guru (404 jika tidak)
    2. Get daftar siswa terdaftar untuk jadwal
    3. Loop setiap nilai yang dikirim:
        - Skip jika kosong
        - Validasi range 0-100
        - Cek siswa terdaftar
        - Query existing record
        - **UPDATE** jika ada OR **INSERT** jika baru
    4. Redirect dengan success message

## Form Data Structure

### HTML Form

```html
<form id="nilaiForm" action="/guru/input-nilai-bulk" method="POST">
    @csrf

    <input type="hidden" name="id_jadwal" value="JD1" />

    <!-- Desktop Table -->
    <table>
        <tr>
            <td>
                <input
                    type="number"
                    name="nilai[SW01]"
                    min="0"
                    max="100"
                    value="85"
                />
            </td>
            <td>
                <textarea name="komentar[SW01]">Bagus</textarea>
            </td>
        </tr>
        <!-- ... more rows ... -->
    </table>

    <button type="submit">Simpan</button>
</form>
```

### JavaScript Generation

```javascript
// Setiap input dibuat dinamis saat jadwal dipilih:
// Input name format: name="nilai[id_siswa]" dan name="komentar[id_siswa]"

data.siswa.forEach((siswa) => {
    const tr = document.createElement("tr");
    tr.innerHTML = `
        <input name="nilai[${siswa.id_siswa}]" type="number" value="${existingNilai}" />
        <textarea name="komentar[${siswa.id_siswa}]">${existingKomentar}</textarea>
    `;
    tableBody.appendChild(tr);
});
```

## Database Operations

### INSERT Example

```sql
INSERT INTO laporan_perkembangan
  (id_laporan, id_siswa, id_mata_pelajaran, nilai, komentar, created_at, updated_at)
VALUES
  ('LP008', 'SW03', 'MP1', 95, 'Excellent', NOW(), NOW());
```

### UPDATE Example

```sql
UPDATE laporan_perkembangan
SET nilai = 85, komentar = 'Bagus', updated_at = NOW()
WHERE id_siswa = 'SW01' AND id_mata_pelajaran = 'MP1';
```

### ID Generation (untuk INSERT)

```php
$maxId = LaporanPerkembangan::orderByRaw('CAST(SUBSTRING(id_laporan, 3) AS UNSIGNED) DESC')
    ->limit(1)
    ->pluck('id_laporan')
    ->first(); // 'LP007'

$nextNumber = (int) substr($maxId, 2) + 1; // 8
$nextId = 'LP'.str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT); // 'LP008'
```

## Validasi

### Client-side (JavaScript)

```javascript
// File: bulk-create.blade.php lines 369-388
const value = input.value.trim();

if (value === "") {
    // Empty is allowed - will be skipped
    input.classList.remove("invalid");
} else if (isNaN(value) || value < 0 || value > 100) {
    // Invalid format
    input.classList.add("invalid");
    hasError = true;
} else {
    // Valid
    input.classList.remove("invalid");
}
```

### Server-side (PHP)

```php
// File: InputNilaiController.php lines 186-193
$nilaiInt = (int) $nilai;
if (! is_numeric($nilai) || $nilaiInt < 0 || $nilaiInt > 100) {
    $errors[] = "Nilai siswa (ID: $id_siswa) tidak valid (harus 0-100).";
    continue;
}

if (! in_array($id_siswa, $registeredSiswa)) {
    $errors[] = "Siswa (ID: $id_siswa) tidak terdaftar untuk jadwal ini.";
    continue;
}
```

## Error Handling

### Skenario Error

1. **Jadwal tidak milik guru** → 404 Not Found
2. **Tidak ada nilai yang dimasukkan** → Redirect dengan pesan error
3. **Nilai di luar range 0-100** → Skip, tambahkan ke error messages
4. **Siswa tidak terdaftar** → Skip, tambahkan ke error messages

### Response Message

```
"Berhasil menyimpan nilai untuk 3 siswa. Nilai siswa (ID: SW04) tidak valid (harus 0-100)."
```

## Performance Notes

-   **Bulk INSERT/UPDATE**: Setiap siswa diproses dalam loop
-   **Query count**: O(n) di mana n = jumlah siswa
-   **Optimization**: Bisa menggunakan batch insert di masa depan
-   **Max siswa per jadwal**: Tidak ada limit teknis

## Testing

### Test Scenarios (Verified via Tinker)

#### Scenario 1: Update Existing

```
Input: nilai[SW01]=95, nilai[SW02]=88
Before: SW01=85, SW02=90
After:  SW01=95, SW02=88
Result: ✓ UPDATE bekerja
```

#### Scenario 2: Mix of Insert & Update

```
Input: nilai[SW01]=92, nilai[SW02]=85, nilai[SW03]=78
Before: SW01 & SW02 exists, SW03 doesn't
After:  SW01 updated, SW02 updated, SW03 inserted
Result: ✓ INSERT dan UPDATE bekerja
```

#### Scenario 3: Skip Empty

```
Input: nilai[SW01]='', nilai[SW02]=88
Before: SW02=90
After:  SW01 unchanged, SW02=88
Result: ✓ Empty values correctly skipped
```

## Features

✅ **Bulk Input** - Input nilai untuk multiple siswa sekaligus
✅ **Smart Update/Insert** - Otomatis detect update vs insert
✅ **AJAX Loading** - Siswa list dimuat dinamis saat jadwal dipilih
✅ **Validation** - Client-side dan server-side validation
✅ **Responsive Design** - Desktop table + mobile cards
✅ **Bulk Actions** - Isi Semua / Hapus Semua buttons
✅ **Error Messages** - Detailed error messages ditampilkan

## Troubleshooting

### Nilai tidak tersimpan?

1. Check browser console untuk JavaScript errors
2. Check Laravel log di `storage/logs/laravel.log`
3. Pastikan jadwal milik guru yang login
4. Pastikan form data terkirim (check Network tab di DevTools)

### Form tidak muncul?

1. Pastikan jadwal sudah dipilih
2. Check browser console untuk AJAX errors
3. Pastikan ada siswa terdaftar untuk jadwal

### Nilai tampil sebelumnya?

1. View sudah membaca dari database dengan benar
2. Data di pre-populate dari AJAX response
3. Semua nilai dari database ditampilkan

## Files Involved

-   **Controller**: `app/Http/Controllers/Guru/InputNilaiController.php`
    -   Methods: `bulkIndex()`, `loadSiswaByJadwal()`, `bulkStore()`
-   **View**: `resources/views/guru/input-nilai/bulk-create.blade.php`
    -   Dual interface: Desktop table + Mobile cards
    -   JavaScript untuk dynamic loading dan form generation
-   **Routes**: `routes/web.php` (lines 65-67)
    -   GET `/input-nilai-bulk`
    -   GET `/kelola-nilai-load-siswa`
    -   POST `/input-nilai-bulk`
-   **Models**:
    -   `App\Models\Guru` - with `jadwal()` relationship
    -   `App\Models\Jadwal` - with `siswa()` and `mataPelajaran()` relationships
    -   `App\Models\LaporanPerkembangan` - nilai records

## Future Improvements

-   [ ] Batch insert optimization (single query vs loop)
-   [ ] Export/import nilai via CSV
-   [ ] Nilai history/audit trail
-   [ ] Template nilai default per mapel
-   [ ] Bulk edit komentar
-   [ ] Conditional formatting based on nilai range
