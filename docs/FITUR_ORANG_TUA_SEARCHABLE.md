# Fitur Searchable Dropdown dan Tambah Orang Tua Cepat

## Deskripsi

Fitur ini memungkinkan admin untuk:

1. **Mencari orang tua** dengan mudah menggunakan dropdown yang dapat dicari (searchable dropdown)
2. **Menambahkan orang tua baru** langsung dari halaman tambah siswa tanpa harus pindah halaman
3. **Auto-select orang tua baru** yang baru ditambahkan untuk siswa yang sedang diinput

## Halaman yang Terpengaruh

-   `/admin/siswa/create` - Halaman tambah siswa baru

## Teknologi yang Digunakan

-   **Select2** - Library untuk membuat dropdown yang dapat dicari
-   **jQuery** - Untuk handling AJAX dan manipulasi DOM
-   **AJAX** - Untuk komunikasi asynchronous dengan server

## Fitur Detail

### 1. Searchable Dropdown Orang Tua

-   Dropdown orang tua sekarang menggunakan Select2 dengan fitur AJAX search
-   User dapat mengetik nama, email, atau no. telepon untuk mencari orang tua
-   Hasil pencarian ditampilkan secara real-time
-   Data dimuat dari endpoint: `GET /admin/siswa-search-orangtua`

### 2. Button Tambah Orang Tua Baru

-   Button hijau **"+ Tambah Baru"** berada di samping dropdown orang tua
-   Saat diklik, akan membuka modal popup untuk input data orang tua baru
-   Modal mencakup form dengan field:
    -   Nama Orang Tua (required)
    -   Email (required, unique)
    -   No. Telepon (required)
    -   Password (required, min 6 karakter)

### 3. Modal Popup Tambah Orang Tua

-   Modal yang smooth dengan animasi fade-in dan slide-down
-   Form validation dengan tampilan error message yang jelas
-   Submit via AJAX ke endpoint: `POST /admin/siswa-store-orangtua`
-   Loading state saat menyimpan data
-   Auto-close setelah berhasil menyimpan

### 4. Auto-Select Orang Tua Baru

-   Setelah orang tua baru berhasil ditambahkan, data tersebut:
    -   Otomatis ditambahkan ke dropdown
    -   Otomatis dipilih/selected
    -   Siap untuk disimpan bersama data siswa

## Routes yang Ditambahkan

```php
// routes/web.php
Route::get('/siswa-search-orangtua', [SiswaController::class, 'searchOrangTua'])->name('siswa.search-orangtua');
Route::post('/siswa-store-orangtua', [SiswaController::class, 'storeOrangTua'])->name('siswa.store-orangtua');
```

## Methods Controller yang Ditambahkan

### searchOrangTua()

```php
public function searchOrangTua(Request $request): JsonResponse
```

-   Menerima query parameter `q` untuk search term
-   Mencari di field: nama, email, no_telpon
-   Return format Select2: `{results: [{id, text}], pagination: {more: false}}`

### storeOrangTua()

```php
public function storeOrangTua(Request $request): JsonResponse
```

-   Validasi: nama, email (unique), no_telpon, password
-   Generate ID otomatis: `OT001`, `OT002`, dst
-   Hash password menggunakan Laravel Hash
-   Return: `{success: true, message: string, data: {id, text}}`

## Cara Penggunaan

### Mencari Orang Tua

1. Buka halaman tambah siswa
2. Klik pada dropdown "Orang Tua"
3. Ketik nama, email, atau no. telepon orang tua yang dicari
4. Pilih dari hasil pencarian

### Menambah Orang Tua Baru

1. Klik button **"+ Tambah Baru"** di samping dropdown orang tua
2. Isi form yang muncul di modal:
    - Nama lengkap orang tua
    - Email (akan digunakan untuk login)
    - No. telepon
    - Password (minimal 6 karakter)
3. Klik tombol **"Simpan"**
4. Orang tua baru akan otomatis terpilih di dropdown
5. Lanjutkan mengisi data siswa dan simpan

## Dependencies

-   jQuery v3.6.0
-   Select2 v4.1.0-rc.0

## Validasi Error Messages

-   **Nama**: "Nama orang tua wajib diisi"
-   **Email**: "Email wajib diisi", "Format email tidak valid", "Email sudah terdaftar"
-   **No. Telepon**: "No. telepon wajib diisi"
-   **Password**: "Password wajib diisi", "Password minimal 6 karakter"

## UI/UX Features

-   Smooth modal animation (fade-in + slide-down)
-   Loading state pada button submit
-   Clear error messages dengan icon
-   Responsive design (mobile-friendly)
-   Click outside modal to close
-   ESC key support untuk close modal

## Security

-   CSRF token protection untuk semua AJAX requests
-   Password di-hash menggunakan bcrypt
-   Email validation dan unique check
-   Input sanitization oleh Laravel validation

## Testing

Untuk menguji fitur ini:

1. Pastikan server Laravel running: `php artisan serve`
2. Login sebagai admin
3. Navigasi ke "Data Siswa" > "Tambah Siswa"
4. Test dropdown search dengan mengetik nama orang tua
5. Test tambah orang tua baru dengan klik button "Tambah Baru"
6. Verifikasi orang tua baru ter-select otomatis setelah disimpan

## Troubleshooting

### Select2 tidak muncul

-   Pastikan jQuery dimuat sebelum Select2
-   Cek console browser untuk error JavaScript
-   Pastikan npm packages sudah terinstall: `npm install`

### AJAX tidak bekerja

-   Cek CSRF token sudah diset di meta tag
-   Verifikasi routes sudah terdaftar: `php artisan route:list | grep siswa`
-   Cek Network tab di browser developer tools

### Orang tua tidak ter-select otomatis

-   Cek response dari endpoint `/siswa-store-orangtua`
-   Pastikan format response sesuai: `{success: true, data: {id, text}}`

## Future Improvements

-   [ ] Tambahkan fitur edit orang tua inline
-   [ ] Tambahkan fitur upload foto orang tua
-   [ ] Implementasi infinite scroll untuk dropdown dengan banyak data
-   [ ] Tambahkan fitur duplicate check sebelum save
-   [ ] Export/import data orang tua via Excel
