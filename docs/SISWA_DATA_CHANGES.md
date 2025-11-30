# Perubahan Data Siswa - Penghapusan Email dan Nomor Telepon

## Tanggal: 30 November 2025

## Alasan Perubahan

Anak TK tidak perlu memiliki email atau nomor telepon pribadi. Informasi kontak yang relevan sudah tersimpan di data orang tua/wali mereka.

## Struktur Database

Tabel `siswa` **TIDAK** memiliki kolom email atau nomor telepon. Strukturnya sudah benar sejak awal:

```sql
CREATE TABLE siswa (
    id_siswa VARCHAR(4) PRIMARY KEY,
    nama VARCHAR(255),
    jenis_kelamin ENUM('L', 'P'),
    tempat_lahir VARCHAR(50),
    tanggal_lahir DATE,
    kelas VARCHAR(20),
    alamat VARCHAR(100),
    id_orang_tua VARCHAR(4),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (id_orang_tua) REFERENCES orang_tua(id_orang_tua)
);
```

## File yang Diubah

### 1. View Files (Penghapusan Tampilan Email/Telepon Siswa)

#### `resources/views/orangtua/anak/show.blade.php`

-   ❌ Dihapus: Field "Email" dan "No. Telepon" dari informasi pribadi siswa
-   ✅ Tetap menampilkan: Nama, Kelas, Alamat, dan informasi lainnya

#### `resources/views/orangtua/anak/index.blade.php`

-   ❌ Dihapus: Baris email (ikon envelope) dan nomor telepon (ikon phone) dari card siswa
-   ✅ Tetap menampilkan: Kelas dan Alamat

#### `resources/views/admin/siswa/index.blade.php`

-   ❌ Dihapus: Kolom "Email" dari tabel siswa
-   ❌ Dihapus: Field email dan nomor telepon dari mobile view
-   ✅ Diubah: Placeholder pencarian dari "Cari nama, ID, atau email..." menjadi "Cari nama atau ID siswa..."
-   ✅ Diperbaiki: Mobile view sekarang menampilkan "Orang Tua: [nama]" bukan hanya nama
-   ⚠️ **Catatan**: Email dan telepon yang ditampilkan seharusnya dari orang tua, bukan siswa

#### `resources/views/dashboards/admin.blade.php`

-   ❌ Dihapus: Kolom "Email" dari tabel "Siswa Terbaru" di dashboard
-   ✅ Tetap menampilkan: Nama Siswa, Kelas, Orang Tua

### 2. View Files yang BENAR (Tidak Perlu Diubah)

#### `resources/views/admin/siswa/show.blade.php`

-   ✅ Sudah benar: Menampilkan email dan telepon dari `$siswa->orangTua->email` dan `$siswa->orangTua->no_telpon`

#### `resources/views/guru/siswa/show.blade.php`

-   ✅ Sudah benar: Menampilkan "Email Orang Tua" dari `$siswa->orangTua->email`

#### `resources/views/admin/siswa/create.blade.php`

-   ✅ Sudah benar: Dropdown orang tua menampilkan email untuk identifikasi
-   ✅ Tidak ada input email/telepon untuk siswa

#### `resources/views/admin/siswa/edit.blade.php`

-   ✅ Sudah benar: Dropdown orang tua menampilkan email untuk identifikasi
-   ✅ Tidak ada input email/telepon untuk siswa

### 3. Model dan Controller (Sudah Bersih)

#### `app/Models/Siswa.php`

✅ `$fillable` tidak memiliki 'email' atau 'no_telpon'

```php
protected $fillable = [
    'id_siswa',
    'nama',
    'jenis_kelamin',
    'tempat_lahir',
    'tanggal_lahir',
    'kelas',
    'alamat',
    'id_orang_tua',
];
```

#### `app/Http/Controllers/Admin/SiswaController.php`

✅ Validation rules tidak memiliki email atau no_telpon
✅ Store dan Update tidak menyimpan email atau no_telpon

#### `database/seeders/DemoDataSeeder.php`

✅ Data siswa tidak memiliki email atau no_telpon

#### `database/factories/SiswaFactory.php`

✅ Factory tidak generate email atau no_telpon

## Cara Mengakses Kontak Orang Tua

Jika perlu menampilkan kontak, gunakan relasi ke orang tua:

```php
// Di Controller
$siswa = Siswa::with('orangTua')->find($id);

// Di Blade
{{ $siswa->orangTua->email }}
{{ $siswa->orangTua->no_telpon }}
```

## Contoh Penggunaan yang Benar

### ✅ Benar - Menampilkan Kontak Orang Tua

```blade
<div>
    <label>Email Orang Tua</label>
    <p>{{ $siswa->orangTua->email ?? '-' }}</p>
</div>

<div>
    <label>Telepon Orang Tua</label>
    <p>{{ $siswa->orangTua->no_telpon ?? '-' }}</p>
</div>
```

### ❌ Salah - Mencoba Akses Email/Telepon Siswa

```blade
<div>
    <label>Email</label>
    <p>{{ $siswa->email }}</p> <!-- ❌ Siswa tidak punya email -->
</div>

<div>
    <label>Telepon</label>
    <p>{{ $siswa->no_telpon }}</p> <!-- ❌ Siswa tidak punya nomor telepon -->
</div>
```

## Testing

Setelah perubahan ini, pastikan untuk test:

1. ✅ View detail siswa (admin, guru, orangtua) - tidak ada error
2. ✅ Create siswa baru - form tidak meminta email/telepon siswa
3. ✅ Edit siswa - form tidak meminta email/telepon siswa
4. ✅ List siswa - tidak menampilkan kolom email siswa
5. ✅ Dashboard - tabel siswa tidak menampilkan email
6. ✅ Kontak orang tua masih bisa diakses via relasi

## Validasi Script

Jalankan script berikut untuk memastikan tidak ada referensi tersisa:

```bash
# Cari referensi email siswa di PHP files
grep -r "siswa->email\|siswa\['email'\]" app/ resources/ database/

# Cari referensi telepon siswa di PHP files
grep -r "siswa->no_telpon\|siswa\['no_telpon'\]" app/ resources/ database/

# Cari di Blade files
grep -r "\$siswa->email\|\$anak->email\|\$child->email" resources/views/

grep -r "\$siswa->no_telpon\|\$anak->no_telpon\|\$child->no_telpon" resources/views/
```

Semua hasil harus kosong atau hanya menunjukkan `$siswa->orangTua->email` (yang benar).

## Kesimpulan

✅ Kolom email dan nomor telepon siswa berhasil dihapus dari semua view
✅ Database migration sudah benar sejak awal (tidak ada kolom email/telepon di tabel siswa)
✅ Model dan Controller sudah bersih
✅ Kontak orang tua tetap dapat diakses melalui relasi `$siswa->orangTua`
✅ Semua file sudah diformat dengan Laravel Pint
