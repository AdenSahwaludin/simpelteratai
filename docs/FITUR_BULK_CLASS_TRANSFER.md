# Fitur Pindah Kelas Massal (Bulk Class Transfer)

## Ringkasan

Fitur baru yang memungkinkan admin untuk memindahkan beberapa siswa ke kelas lain secara bersamaan dalam satu proses. Fitur ini meningkatkan efisiensi dalam manajemen data siswa, terutama saat kenaikan kelas atau reorganisasi kelas.

---

## Fitur Utama

### 1. **Pemilihan Kelas Asal**
- Admin dapat memilih kelas asal untuk menampilkan daftar siswa
- Filter otomatis menampilkan semua siswa dari kelas yang dipilih
- Support semua kelas yang ada dalam sistem

### 2. **Pemilihan Siswa**
- Checkbox individual untuk setiap siswa
- Fitur "Pilih Semua" untuk memudahkan seleksi
- Counter real-time menampilkan jumlah siswa terpilih
- Tampilan informasi lengkap: nama siswa, ID, dan orang tua

### 3. **Penentuan Kelas Tujuan**
- Input field fleksibel untuk kelas tujuan
- Dapat memindahkan ke kelas yang sudah ada
- Dapat membuat kelas baru secara otomatis
- Validasi untuk memastikan kelas tujuan terisi

### 4. **Proses Perpindahan**
- Konfirmasi sebelum proses dilaksanakan
- Update database secara batch untuk efisiensi
- Notifikasi sukses dengan jumlah siswa yang dipindahkan
- Redirect otomatis ke halaman hasil

---

## File yang Dibuat/Dimodifikasi

### 1. Routes (`routes/web.php`)
```php
// Bulk class transfer routes
Route::get('/siswa-bulk-transfer', [SiswaController::class, 'showBulkTransfer'])
    ->name('siswa.bulk-transfer');
Route::post('/siswa-bulk-transfer', [SiswaController::class, 'processBulkTransfer'])
    ->name('siswa.bulk-transfer.process');
```

### 2. Controller (`app/Http/Controllers/Admin/SiswaController.php`)
**Method Baru:**
- `showBulkTransfer()` - Menampilkan form perpindahan kelas massal
- `processBulkTransfer()` - Memproses perpindahan kelas massal

### 3. View (`resources/views/admin/siswa/bulk-transfer.blade.php`)
**Komponen:**
- Form pemilihan kelas asal
- Grid siswa dengan checkbox
- Input kelas tujuan
- JavaScript untuk interaksi checkbox

### 4. Index Page (`resources/views/admin/siswa/index.blade.php`)
**Tambahan:**
- Tombol "Pindah Kelas Massal" di header halaman
- Styling purple untuk membedakan dari tombol lain

### 5. Dokumentasi Testing (`docs/5.2.3.2_Pengujian_Black_Box_TK_Teratai.md`)
**Skenario Testing Baru (7 skenario):**
- Pilih kelas asal
- Pilih beberapa siswa
- Pilih semua siswa
- Proses perpindahan
- Validasi tanpa siswa terpilih
- Validasi tanpa kelas tujuan
- Perpindahan ke kelas baru

---

## Cara Penggunaan

### Langkah 1: Akses Fitur
1. Login sebagai Admin
2. Buka menu "Data Siswa"
3. Klik tombol **"Pindah Kelas Massal"** (warna purple)

### Langkah 2: Pilih Kelas Asal
1. Pilih kelas asal dari dropdown
2. Klik **"Tampilkan Siswa"**
3. Sistem akan menampilkan semua siswa di kelas tersebut

### Langkah 3: Pilih Siswa
1. Centang siswa yang ingin dipindahkan secara individual, atau
2. Klik checkbox **"Pilih Semua Siswa"** untuk memilih semua
3. Counter akan menampilkan jumlah siswa terpilih

### Langkah 4: Tentukan Kelas Tujuan
1. Masukkan nama kelas tujuan (contoh: 5A, 6B)
2. Dapat menggunakan kelas yang sudah ada atau membuat kelas baru
3. Klik **"Proses Perpindahan Kelas"**

### Langkah 5: Konfirmasi
1. Sistem akan menampilkan dialog konfirmasi
2. Periksa jumlah siswa dan kelas tujuan
3. Klik OK untuk melanjutkan atau Cancel untuk membatalkan

### Langkah 6: Selesai
1. Sistem menampilkan notifikasi sukses
2. Halaman otomatis menampilkan siswa di kelas tujuan
3. Data siswa telah dipindahkan ke kelas baru

---

## Validasi

### Server-Side Validation
- **siswa_ids**: Required, array, minimal 1 siswa
- **target_kelas**: Required, string, maksimal 255 karakter

### Client-Side Validation
- Alert jika tidak ada siswa terpilih
- Alert jika kelas tujuan kosong
- Konfirmasi sebelum proses

---

## Keamanan

### Middleware
- `check.admin.role` - Hanya admin yang dapat akses

### Authorization
- Route berada dalam group admin
- Memerlukan autentikasi sebagai admin

### Validation
- Input sanitization untuk mencegah SQL injection
- CSRF token protection pada form
- Validasi exists untuk memastikan siswa ada

---

## Testing

### Skenario Testing yang Dicakup
1. ✅ Pilih kelas asal dan tampilkan siswa
2. ✅ Pilih beberapa siswa untuk dipindahkan
3. ✅ Pilih semua siswa dengan satu klik
4. ✅ Proses perpindahan ke kelas yang ada
5. ✅ Proses perpindahan ke kelas baru
6. ✅ Validasi error tanpa siswa terpilih
7. ✅ Validasi error tanpa kelas tujuan

### Testing Manual
```bash
# 1. Login sebagai admin
# 2. Navigasi ke /admin/siswa-bulk-transfer
# 3. Pilih kelas asal (misal: 5A)
# 4. Centang beberapa siswa
# 5. Masukkan kelas tujuan (misal: 5B)
# 6. Klik tombol proses
# 7. Verifikasi siswa berpindah ke kelas 5B
```

---

## Manfaat Fitur

### Efisiensi Operasional
- **Hemat Waktu**: Pindahkan 20+ siswa dalam sekali proses vs. 20x proses individual
- **Mengurangi Error**: Proses batch mengurangi kesalahan manual
- **User-Friendly**: Interface intuitif dengan visual feedback

### Use Cases
1. **Kenaikan Kelas Tahunan**: Pindahkan seluruh kelas 5A ke 6A
2. **Reorganisasi Kelas**: Bagi kelas besar menjadi dua kelas lebih kecil
3. **Koreksi Kesalahan**: Pindahkan siswa yang salah penempatan
4. **Pengelompokan Ulang**: Gabungkan dua kelas kecil menjadi satu

---

## Statistik Kode

- **Lines of Code**: ~250 lines
  - Controller: ~50 lines
  - View: ~180 lines
  - Route: ~4 lines
  - Test Scenarios: ~7 scenarios

- **Files Modified**: 4 files
- **Files Created**: 1 file
- **Test Scenarios Added**: 7 scenarios

---

## Update Documentation

### Black Box Testing
- Total skenario testing bertambah dari **85+** menjadi **92+**
- Dokumentasi diperbarui per 14 Januari 2026

---

## Future Enhancements

### Potential Improvements
1. **Export/Import**: Export daftar siswa ke Excel sebelum pindah
2. **History Log**: Catat riwayat perpindahan kelas
3. **Undo Feature**: Kembalikan perpindahan yang salah
4. **Email Notification**: Kirim notifikasi ke orang tua tentang perpindahan kelas
5. **Bulk Edit**: Edit data lain selain kelas secara massal
6. **Filter Advanced**: Filter berdasarkan jenis kelamin, usia, dll.
7. **Preview**: Preview siswa sebelum konfirmasi perpindahan

---

## Troubleshooting

### Issue: Siswa tidak muncul setelah pilih kelas
**Solusi**: Pastikan ada siswa di kelas tersebut. Sistem akan menampilkan pesan jika kelas kosong.

### Issue: Form tidak submit
**Solusi**: 
- Pastikan minimal 1 siswa terpilih
- Pastikan kelas tujuan terisi
- Periksa JavaScript console untuk error

### Issue: Perpindahan tidak tersimpan
**Solusi**:
- Periksa koneksi database
- Verifikasi validasi form
- Cek Laravel logs di `storage/logs/laravel.log`

---

## Kontak & Support

Untuk pertanyaan atau bug report terkait fitur ini, hubungi tim development atau buat issue di repository project.

---

**Dibuat**: 14 Januari 2026  
**Versi**: 1.0  
**Author**: GitHub Copilot  
**Status**: ✅ Production Ready
