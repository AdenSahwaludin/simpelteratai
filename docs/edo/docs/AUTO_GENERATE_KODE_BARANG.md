# Auto-Generate Kode Barang Feature

## 📝 Overview

Fitur auto-generate kode barang dengan format: **KATEGORI(3)-NAMA(2)-LOKASI(2)-SEQ(3)**

Contoh: `ELE-KL-RG-001`

-   `ELE` = 3 huruf pertama dari "Elektronik"
-   `KL` = Inisial 2 huruf dari "Kursi Lipat" (K + L)
-   `RG` = Inisial 2 huruf dari "Ruang Guru" (R + G)
-   `001` = Nomor urut 3 digit (auto increment)

## ✨ Features

### 1. Ekstraksi Otomatis

-   **Kategori (3 char)**: Mengambil 3 huruf pertama dari nama kategori

    -   "Elektronik" → `ELE`
    -   "Furniture" → `FUR`
    -   "Alat Tulis" → `ALA`

-   **Nama Barang (2 char)**: Mengambil inisial dari kata pertama dan kedua

    -   "Kursi Lipat" → `KL` (K dari Kursi, L dari Lipat)
    -   "Komputer Dell" → `KD` (K dari Komputer, D dari Dell)
    -   "Meja" → `ME` (2 huruf pertama jika hanya 1 kata)

-   **Lokasi (2 char)**: Mengambil inisial dari kata pertama dan kedua
    -   "Ruang Guru" → `RG`
    -   "Lab Komputer" → `LK`
    -   "Gudang" → `GU` (2 huruf pertama jika hanya 1 kata)

### 2. Auto-Increment

-   Sistem akan otomatis mencari nomor urut terakhir dengan prefix yang sama
-   Jika `ELE-KL-RG-001` sudah ada, maka yang baru akan menjadi `ELE-KL-RG-002`
-   Bahkan data yang sudah dihapus (soft deleted) tetap dihitung untuk menghindari duplikasi

### 3. Real-Time Update di Form

Kode barang akan otomatis di-generate di form saat user:

1. Memilih **Kategori** (dropdown)
2. Mengisi **Nama Barang** (text input)
3. Memilih **Lokasi** (dropdown)

Field kode barang bersifat **disabled** (read-only) tapi tetap tersimpan ke database.

## 🔧 Implementation Details

### 1. Model Method (`app/Models/Barang.php`)

```php
public static function generateKodeBarang(?int $kategoriId, ?int $lokasiId, ?string $namaBarang): string
```

Method ini:

-   Menerima kategori_id, lokasi_id, dan nama_barang
-   Mengambil data kategori dan lokasi dari database
-   Ekstrak kode berdasarkan aturan di atas
-   Generate nomor urut dengan query last record
-   Return format lengkap: `XXX-XX-XX-001`

### 2. Observer Hook (`app/Observers/BarangObserver.php`)

```php
public function creating(Barang $barang): void
{
    if (empty($barang->kode_barang)) {
        $barang->kode_barang = Barang::generateKodeBarang(...);
    }
}
```

Observer ini sebagai **fallback** jika kode_barang belum ter-generate (misalnya dari seeder atau API).

### 3. Form Integration (`app/Filament/Resources/Barangs/Schemas/BarangForm.php`)

```php
TextInput::make('kode_barang')
    ->disabled()
    ->dehydrated()
    ->live()
    ->afterStateUpdated(function (Get $get, Set $set) {
        // Auto-generate kode
    })
```

-   Field **disabled** tapi **dehydrated** (tetap disimpan)
-   Real-time update dengan `->live()`
-   Trigger pada perubahan kategori, nama barang, atau lokasi

## ✅ Testing

6 test cases telah dibuat dan **semua lulus**:

1. ✓ **Format dasar**: "ELE-KL-RG-001"
2. ✓ **Single word**: "Meja" → "ME"
3. ✓ **Auto increment**: 001 → 002 → 003
4. ✓ **Observer fallback**: Generate otomatis saat create tanpa kode
5. ✓ **Multi-word lokasi**: "Lab Komputer" → "LK"
6. ✓ **Multi-word kategori**: "Furniture Kantor" → "FUR"

Run tests:

```bash
php artisan test --filter=AutoGenerateKodeBarangTest
```

## 🎯 Usage Example

### Via Filament UI

1. Buka halaman **Barang** → **Create**
2. Pilih **Kategori**: "Elektronik"
3. Isi **Nama Barang**: "Kursi Lipat"
4. Pilih **Lokasi**: "Ruang Guru"
5. Kode barang akan otomatis muncul: `ELE-KL-RG-001`
6. Isi field lainnya (jumlah stok, harga, dll)
7. Klik **Save**

### Via Code/Tinker

```php
// Generate kode secara manual
$kode = Barang::generateKodeBarang(1, 1, 'Kursi Lipat');
// Output: ELE-KL-RG-001

// Create barang (kode auto-generate via observer)
Barang::create([
    'nama_barang' => 'Komputer Dell',
    'kategori_id' => 1,
    'lokasi_id' => 1,
    'jumlah_stok' => 5,
    'reorder_point' => 2,
    'harga_satuan' => 5000000,
    'status' => 'baik',
]);
// kode_barang akan otomatis: ELE-KD-RG-001
```

## 📋 Benefits

1. **Konsisten**: Format kode selalu seragam
2. **Unik**: Auto-increment mencegah duplikasi
3. **Readable**: Kode mudah dibaca dan dipahami
4. **Efficient**: User tidak perlu input manual
5. **Traceable**: Bisa langsung tahu kategori dan lokasi dari kode

## 🔄 Future Enhancements (Optional)

-   [ ] Custom format per kategori
-   [ ] Prefix tahun (ELE-2025-KL-RG-001)
-   [ ] QR Code generator dari kode_barang
-   [ ] Barcode integration
-   [ ] Bulk code generation

---

**Status**: ✅ **Implemented & Tested**  
**Laravel Version**: 12.43.1  
**Filament Version**: 4.0.0  
**Created**: December 2024
