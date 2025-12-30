# 5.2 Pembahasan

## 5.2.1 Pembahasan Basis Data

Pembahasan basis data yang telah dibangun dapat dilakukan dengan cara menjabarkan penggunaan Data Manipulation Language (DML) pada data sesuai kebutuhan organisasi/instansi/perusahaan. DML mencakup operasi-operasi dasar yakni Create (INSERT), Read (SELECT), Update (UPDATE), dan Delete (DELETE) yang diimplementasikan melalui Eloquent ORM dalam Laravel.

---

## 5.2.1.1 Create Data Barang

Operasi create (INSERT) digunakan untuk menambahkan data barang baru ke dalam sistem inventaris. Data yang disimpan meliputi kode barang, nama, kategori, lokasi, stok awal, reorder point, satuan, status, dan informasi tambahan seperti harga dan merek. Setiap penambahan data barang harus dilakukan melalui form validasi untuk memastikan integritas dan konsistensi data dalam database.

**Sintaks Eloquent ORM:**

```php
// Create single record
$barang = Barang::create([
    'kode_barang' => 'BRG001',
    'nama_barang' => 'Pensil HB',
    'kategori_id' => 1,
    'lokasi_id' => 2,
    'jumlah_stok' => 100,
    'reorder_point' => 10,
    'satuan' => 'pcs',
    'status' => 'baik',
    'harga_satuan' => 2000.00,
    'merk' => 'Faber Castell',
    'tanggal_pembelian' => now(),
]);

// Using fill method
$barang = new Barang();
$barang->fill([
    'kode_barang' => 'BRG001',
    'nama_barang' => 'Pensil HB',
    'kategori_id' => 1,
])->save();

// Bulk create
Barang::insert([
    [
        'kode_barang' => 'BRG001',
        'nama_barang' => 'Pensil HB',
        'kategori_id' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'kode_barang' => 'BRG002',
        'nama_barang' => 'Penghapus',
        'kategori_id' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ],
]);
```

---

## 5.2.1.2 Read Data Barang

Operasi read (SELECT) bertujuan untuk mengambil dan menampilkan data barang dari database sesuai kebutuhan. Data dapat diambil secara keseluruhan, dengan filter kondisi tertentu, atau dengan relasi ke tabel lain seperti kategori dan lokasi. Operasi ini mendukung pagination, sorting, dan filtering untuk memudahkan navigasi data dalam skala besar.

**Sintaks Eloquent ORM:**

```php
// Get all barang
$barang = Barang::all();

// Get with pagination
$barang = Barang::paginate(15);

// Get specific columns
$barang = Barang::select('id', 'nama_barang', 'jumlah_stok')->get();

// Get with where clause
$barang = Barang::where('status', 'baik')
    ->where('jumlah_stok', '>', 0)
    ->get();

// Get with relationship
$barang = Barang::with('kategori', 'lokasi')->get();

// Get single record
$barang = Barang::find(1);
$barang = Barang::where('kode_barang', 'BRG001')->first();

// Get with filtering and sorting
$barang = Barang::where('kategori_id', 1)
    ->orderBy('nama_barang', 'asc')
    ->paginate(10);

// Custom scope
$barang = Barang::stokRendah()->get(); // Stok <= reorder_point
$barang = Barang::byStatus('rusak')->get(); // Filter by status

// Count records
$total = Barang::count();
$stokRendah = Barang::where('jumlah_stok', '<', 10)->count();
```

---

## 5.2.1.3 Update Data Barang

Operasi update (UPDATE) memungkinkan perubahan data barang yang sudah tersimpan di database. Update dapat dilakukan pada satu atau beberapa kolom, baik untuk satu record maupun multiple records sekaligus. Setiap update mencatat waktu perubahan secara otomatis melalui timestamp dan harus melewati validasi sebelum disimpan.

**Sintaks Eloquent ORM:**

```php
// Update single record by ID
$barang = Barang::find(1);
$barang->update([
    'nama_barang' => 'Pensil 2B',
    'jumlah_stok' => 150,
    'harga_satuan' => 2500.00,
]);

// Update with save method
$barang = Barang::find(1);
$barang->nama_barang = 'Pensil 2B';
$barang->jumlah_stok = 150;
$barang->save();

// Update multiple records with where
Barang::where('kategori_id', 1)
    ->update([
        'status' => 'baik',
        'updated_at' => now(),
    ]);

// Increment/Decrement
Barang::find(1)->increment('jumlah_stok', 50); // Tambah stok
Barang::find(1)->decrement('jumlah_stok', 20); // Kurangi stok

// Update using firstOrCreate
$barang = Barang::firstOrCreate(
    ['kode_barang' => 'BRG001'],
    ['nama_barang' => 'Pensil HB', 'kategori_id' => 1]
);

// Update using updateOrCreate
$barang = Barang::updateOrCreate(
    ['kode_barang' => 'BRG001'],
    ['nama_barang' => 'Pensil HB Updated', 'jumlah_stok' => 200]
);
```

---

## 5.2.1.4 Delete Data Barang

Operasi delete (DELETE) digunakan untuk menghapus data barang dari database. Sistem menggunakan soft delete sehingga data tidak benar-benar terhapus melainkan ditandai dengan timestamp deleted_at. Fitur ini memungkinkan pemulihan data jika terjadi kesalahan penghapusan dan mendukung audit trail.

**Sintaks Eloquent ORM:**

```php
// Soft delete single record
$barang = Barang::find(1);
$barang->delete();

// Force delete (permanent)
$barang = Barang::find(1);
$barang->forceDelete();

// Delete multiple records
Barang::where('status', 'hilang')->delete();

// Restore soft deleted record
$barang = Barang::withTrashed()->find(1);
$barang->restore();

// Permanently delete trashed record
$barang = Barang::onlyTrashed()->find(1);
$barang->forceDelete();

// Query only trashed records
$trashedBarang = Barang::onlyTrashed()->get();

// Query including trashed records
$allBarang = Barang::withTrashed()->get();

// Force delete multiple
Barang::where('kategori_id', 1)->forceDelete();

// Restore multiple
Barang::onlyTrashed()
    ->where('kategori_id', 1)
    ->restore();
```

---

## 5.2.1.5 Create Data Kategori

Operasi create pada tabel kategori bertujuan menambahkan jenis/klasifikasi barang baru ke dalam sistem. Setiap kategori diberi nama dan deskripsi untuk memudahkan pengelompokan inventaris. Kategori merupakan data master yang harus ada sebelum membuat data barang untuk menjaga integritas relasi foreign key.

**Sintaks Eloquent ORM:**

```php
// Create kategori
$kategori = Kategori::create([
    'nama_kategori' => 'Alat Tulis',
    'deskripsi' => 'Peralatan tulis kantor dan sekolah',
]);

// Using fill and save
$kategori = new Kategori();
$kategori->fill([
    'nama_kategori' => 'Peralatan Kantor',
    'deskripsi' => 'Perlengkapan umum kantor',
])->save();

// Bulk insert kategori
Kategori::insert([
    [
        'nama_kategori' => 'Alat Tulis',
        'deskripsi' => 'Peralatan tulis kantor',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'nama_kategori' => 'Peralatan Teknologi',
        'deskripsi' => 'Perangkat dan aksesori teknologi',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'nama_kategori' => 'Perlengkapan Laboratorium',
        'deskripsi' => 'Alat dan bahan laboratorium',
        'created_at' => now(),
        'updated_at' => now(),
    ],
]);
```

---

## 5.2.1.6 Read Data Kategori

Operasi read pada kategori mengambil data jenis-jenis barang dari database untuk ditampilkan dalam form dropdown atau tabel. Data kategori dapat diambil seluruhnya atau dengan relasi ke tabel barang untuk melihat berapa banyak barang dalam setiap kategori. Kategori sering digunakan sebagai filter dalam query barang untuk pencarian dan reporting.

**Sintaks Eloquent ORM:**

```php
// Get all kategori
$kategori = Kategori::all();

// Get with relationship count
$kategori = Kategori::withCount('barang')->get();

// Get kategori with barang
$kategori = Kategori::with('barang')->get();

// Get with pagination
$kategori = Kategori::paginate(10);

// Search by name
$kategori = Kategori::where('nama_kategori', 'like', '%Alat%')
    ->get();

// Get single kategori
$kategori = Kategori::find(1);
$kategori = Kategori::where('nama_kategori', 'Alat Tulis')->first();

// Get kategori with active barang only
$kategori = Kategori::with([
    'barang' => function($query) {
        $query->where('status', 'baik');
    }
])->get();

// Get kategori ordered by name
$kategori = Kategori::orderBy('nama_kategori', 'asc')->get();
```

---

## 5.2.1.7 Update Data Kategori

Operasi update pada kategori mengubah nama atau deskripsi dari jenis barang yang sudah terdaftar. Update pada kategori dapat mempengaruhi semua barang yang terkait karena relasi foreign key. Perubahan nama kategori harus dilakukan dengan hati-hati untuk memastikan konsistensi data dan tidak mengganggu proses bisnis.

**Sintaks Eloquent ORM:**

```php
// Update kategori
$kategori = Kategori::find(1);
$kategori->update([
    'nama_kategori' => 'Perlengkapan Tulis',
    'deskripsi' => 'Semua peralatan menulis dan menggambar',
]);

// Update with save
$kategori = Kategori::find(1);
$kategori->nama_kategori = 'Perlengkapan Tulis';
$kategori->deskripsi = 'Alat menulis dan menggambar';
$kategori->save();

// Update multiple kategori
Kategori::where('nama_kategori', 'like', 'Alat%')
    ->update(['deskripsi' => 'Updated description']);

// updateOrCreate
$kategori = Kategori::updateOrCreate(
    ['nama_kategori' => 'Alat Tulis'],
    ['deskripsi' => 'Peralatan tulis kantor dan sekolah updated']
);
```

---

## 5.2.1.8 Create Data Transaksi Barang

Operasi create transaksi mencatat setiap pergerakan barang masuk atau keluar dari gudang. Transaksi yang dibuat meliputi jenis transaksi (masuk/keluar), barang yang terlibat, jumlah, tanggal, penanggung jawab, dan keterangan. Setiap transaksi secara otomatis mengurangi atau menambah stok barang untuk menjaga akurasi inventaris real-time.

**Sintaks Eloquent ORM:**

```php
// Create transaksi barang masuk
$transaksi = TransaksiBarang::create([
    'kode_transaksi' => 'TRX-IN-001',
    'barang_id' => 1,
    'tipe_transaksi' => 'masuk',
    'jumlah' => 50,
    'tanggal_transaksi' => now()->toDateString(),
    'penanggung_jawab' => 'Budi Santoso',
    'keterangan' => 'Pembelian dari supplier',
    'user_id' => 1,
    'approval_status' => 'pending',
]);

// Create transaksi barang keluar
$transaksi = TransaksiBarang::create([
    'kode_transaksi' => 'TRX-OUT-001',
    'barang_id' => 2,
    'tipe_transaksi' => 'keluar',
    'jumlah' => 10,
    'tanggal_transaksi' => now()->toDateString(),
    'penanggung_jawab' => 'Andi Wijaya',
    'keterangan' => 'Penggunaan untuk kegiatan',
    'user_id' => 2,
    'approval_status' => 'pending',
]);

// Create with auto approval
$transaksi = TransaksiBarang::create([
    'kode_transaksi' => 'TRX-001',
    'barang_id' => 1,
    'tipe_transaksi' => 'masuk',
    'jumlah' => 100,
    'tanggal_transaksi' => now()->toDateString(),
    'user_id' => 1,
    'approved_by' => 3,
    'approved_at' => now(),
    'approval_status' => 'approved',
]);
```

---

## 5.2.1.9 Read Data Transaksi Barang

Operasi read transaksi mengambil riwayat pergerakan barang untuk keperluan pelaporan dan monitoring. Data transaksi dapat ditampilkan dengan filter berdasarkan jenis transaksi, periode, status approval, atau barang spesifik. Operasi ini mendukung grouping dan aggregation untuk analisis tren pergerakan barang dalam kurun waktu tertentu.

**Sintaks Eloquent ORM:**

```php
// Get all transaksi
$transaksi = TransaksiBarang::all();

// Get with relationships
$transaksi = TransaksiBarang::with('barang', 'user', 'approvedBy')
    ->paginate(15);

// Get transaksi masuk
$masuk = TransaksiBarang::masuk()->get();

// Get transaksi keluar
$keluar = TransaksiBarang::keluar()->get();

// Get transaksi pending approval
$pending = TransaksiBarang::pending()->get();

// Get transaksi approved
$approved = TransaksiBarang::where('approval_status', 'approved')->get();

// Get transaksi by period
$transaksi = TransaksiBarang::whereBetween('tanggal_transaksi', [
    now()->startOfMonth(),
    now()->endOfMonth()
])->get();

// Get transaksi by barang
$transaksi = TransaksiBarang::where('barang_id', 1)
    ->orderBy('tanggal_transaksi', 'desc')
    ->paginate(10);

// Get transaksi with sum jumlah
$total = TransaksiBarang::where('barang_id', 1)
    ->where('tipe_transaksi', 'masuk')
    ->sum('jumlah');

// Get grouped by barang
$transaksi = TransaksiBarang::selectRaw(
    'barang_id, tipe_transaksi, SUM(jumlah) as total'
)
->groupBy('barang_id', 'tipe_transaksi')
->get();
```

---

## 5.2.1.10 Create Data Log Aktivitas

Operasi create log aktivitas mencatat setiap aksi pengguna dalam sistem secara otomatis melalui observer. Log mencakup jenis aktivitas (login, create, update, delete), user yang melakukan aksi, tabel yang terpengaruh, dan perubahan data detail dalam format JSON. Sistem logging ini mendukung keamanan dan audit trail untuk compliance requirement organisasi.

**Sintaks Eloquent ORM:**

```php
// Create log aktivitas
$log = LogAktivitas::create([
    'user_id' => 1,
    'jenis_aktivitas' => 'create',
    'nama_tabel' => 'barang',
    'record_id' => 1,
    'deskripsi' => 'Membuat data barang baru: Pensil HB',
    'perubahan_data' => json_encode([
        'kode_barang' => 'BRG001',
        'nama_barang' => 'Pensil HB',
        'jumlah_stok' => 100,
    ]),
    'ip_address' => '192.168.1.1',
    'user_agent' => 'Mozilla/5.0...',
]);

// Create update log
$log = LogAktivitas::create([
    'user_id' => 2,
    'jenis_aktivitas' => 'update',
    'nama_tabel' => 'barang',
    'record_id' => 1,
    'deskripsi' => 'Update stok barang',
    'perubahan_data' => json_encode([
        'old' => ['jumlah_stok' => 100],
        'new' => ['jumlah_stok' => 150],
    ]),
    'ip_address' => request()->ip(),
    'user_agent' => request()->userAgent(),
]);

// Create delete log
$log = LogAktivitas::create([
    'user_id' => 3,
    'jenis_aktivitas' => 'delete',
    'nama_tabel' => 'barang',
    'record_id' => 5,
    'deskripsi' => 'Menghapus data barang: Penghapus',
    'ip_address' => request()->ip(),
    'user_agent' => request()->userAgent(),
]);
```

---

## 5.2.1.11 Read Data Log Aktivitas

Operasi read log aktivitas mengambil catatan riwayat perubahan sistem untuk audit dan monitoring. Log dapat difilter berdasarkan user, jenis aktivitas, tabel, atau periode waktu. Operasi ini mendukung pencarian dan sorting untuk investigasi aktivitas spesifik dan analisis trend penggunaan sistem.

**Sintaks Eloquent ORM:**

```php
// Get all logs
$logs = LogAktivitas::all();

// Get logs with pagination
$logs = LogAktivitas::paginate(20);

// Get logs with relationships
$logs = LogAktivitas::with('user')->get();

// Get logs by user
$logs = LogAktivitas::where('user_id', 1)
    ->orderBy('created_at', 'desc')
    ->paginate(10);

// Get logs by activity type
$createLogs = LogAktivitas::jenisAktivitas('create')->get();
$updateLogs = LogAktivitas::jenisAktivitas('update')->get();
$deleteLogs = LogAktivitas::jenisAktivitas('delete')->get();

// Get logs by table
$barangLogs = LogAktivitas::tabel('barang')->get();

// Get logs by period
$monthlyLogs = LogAktivitas::periode(
    now()->startOfMonth(),
    now()->endOfMonth()
)->get();

// Get recent logs
$recent = LogAktivitas::orderBy('created_at', 'desc')
    ->limit(50)
    ->get();

// Search logs
$searchLogs = LogAktivitas::where('deskripsi', 'like', '%Pensil%')
    ->orWhere('user_id', 1)
    ->get();
```

---

**Dokumentasi Pembahasan Basis Data dibuat:** 28 Desember 2025  
**Versi Laravel:** 12.43.1  
**Database Engine:** MySQL  
**ORM:** Eloquent
