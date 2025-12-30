# Dokumentasi Skema Database Sistem Inventaris

## Daftar Isi

1. [Tabel Users (Pengguna)](#1-tabel-users)
2. [Tabel Kategori](#2-tabel-kategori)
3. [Tabel Lokasi](#3-tabel-lokasi)
4. [Tabel Barang](#4-tabel-barang)
5. [Tabel Barang Rusak](#5-tabel-barang-rusak)
6. [Tabel Transaksi Barang](#6-tabel-transaksi-barang)
7. [Tabel Log Aktivitas](#7-tabel-log-aktivitas)
8. [Tabel Backup Logs](#8-tabel-backup-logs)

---

## 1. Tabel Users

**Penjelasan:** Tabel pengguna menyimpan data login dan profil semua pengguna sistem. Setiap pengguna memiliki kredensial unik dan peran untuk menentukan akses fitur inventaris serta laporan manajemen barang.

**Nama Tabel:** `users`

| No. | Nama Field        | Tipe Data    | Constraint         | Penjelasan                  |
| --- | ----------------- | ------------ | ------------------ | --------------------------- |
| 1   | id                | BIGINT       | PK, AUTO_INCREMENT | ID unik pengguna            |
| 2   | name              | VARCHAR(255) | NOT NULL           | Nama lengkap pengguna       |
| 3   | email             | VARCHAR(255) | UNIQUE, NOT NULL   | Email unik untuk login      |
| 4   | email_verified_at | TIMESTAMP    | NULLABLE           | Waktu verifikasi email      |
| 5   | password          | VARCHAR(255) | NOT NULL           | Password terenkripsi bcrypt |
| 6   | remember_token    | VARCHAR(100) | NULLABLE           | Token untuk "remember me"   |
| 7   | is_active         | BOOLEAN      | DEFAULT(true)      | Status aktif pengguna       |
| 8   | created_at        | TIMESTAMP    | NOT NULL           | Waktu pembuatan record      |
| 9   | updated_at        | TIMESTAMP    | NOT NULL           | Waktu update record         |
| 10  | deleted_at        | TIMESTAMP    | NULLABLE           | Soft delete timestamp       |

**Relasi:**

-   HasMany → TransaksiBarang (user_id)
-   HasMany → LogAktivitas (user_id)
-   HasMany → BackupLog (user_id)
-   HasMany → BarangRusak (user_id)

---

## 2. Tabel Kategori

**Penjelasan:** Tabel kategori mengelompokkan barang berdasarkan jenisnya seperti alat tulis, peralatan kantor, atau perlengkapan laboratorium. Setiap barang harus masuk dalam kategori untuk memudahkan pencarian dan klasifikasi inventaris.

**Nama Tabel:** `kategori`

| No. | Nama Field    | Tipe Data    | Constraint         | Penjelasan                 |
| --- | ------------- | ------------ | ------------------ | -------------------------- |
| 1   | id            | BIGINT       | PK, AUTO_INCREMENT | ID unik kategori           |
| 2   | nama_kategori | VARCHAR(100) | NOT NULL           | Nama jenis/kategori barang |
| 3   | deskripsi     | TEXT         | NULLABLE           | Penjelasan detail kategori |
| 4   | created_at    | TIMESTAMP    | NOT NULL           | Waktu pembuatan record     |
| 5   | updated_at    | TIMESTAMP    | NOT NULL           | Waktu update record        |
| 6   | deleted_at    | TIMESTAMP    | NULLABLE           | Soft delete timestamp      |

**Index:**

-   nama_kategori (untuk pencarian cepat)

**Relasi:**

-   HasMany → Barang (kategori_id)

---

## 3. Tabel Lokasi

**Penjelasan:** Tabel lokasi mencatat tempat penyimpanan barang dengan informasi gedung, lantai, dan ruangan. Data lokasi membantu tracking fisik barang dan memudahkan pencarian saat audit atau pengambilan barang dari gudang.

**Nama Tabel:** `lokasi`

| No. | Nama Field  | Tipe Data    | Constraint         | Penjelasan                 |
| --- | ----------- | ------------ | ------------------ | -------------------------- |
| 1   | id          | BIGINT       | PK, AUTO_INCREMENT | ID unik lokasi             |
| 2   | nama_lokasi | VARCHAR(100) | NOT NULL           | Nama ruangan/lokasi        |
| 3   | gedung      | VARCHAR(50)  | NULLABLE           | Nama gedung                |
| 4   | lantai      | VARCHAR(20)  | NULLABLE           | Nomor lantai               |
| 5   | keterangan  | TEXT         | NULLABLE           | Keterangan tambahan lokasi |
| 6   | created_at  | TIMESTAMP    | NOT NULL           | Waktu pembuatan record     |
| 7   | updated_at  | TIMESTAMP    | NOT NULL           | Waktu update record        |
| 8   | deleted_at  | TIMESTAMP    | NULLABLE           | Soft delete timestamp      |

**Index:**

-   nama_lokasi (untuk pencarian cepat)

**Relasi:**

-   HasMany → Barang (lokasi_id)

---

## 4. Tabel Barang

**Penjelasan:** Tabel utama barang menyimpan semua aset dengan tracking stok real-time, kategori, lokasi, dan harga. Sistem otomatis memberi peringatan saat stok mencapai reorder point untuk mencegah kehabisan barang penting.

**Nama Tabel:** `barang`

| No. | Nama Field        | Tipe Data     | Constraint         | Penjelasan                    |
| --- | ----------------- | ------------- | ------------------ | ----------------------------- |
| 1   | id                | BIGINT        | PK, AUTO_INCREMENT | ID unik barang                |
| 2   | kode_barang       | VARCHAR(50)   | UNIQUE, NOT NULL   | Kode identitas barang         |
| 3   | nama_barang       | VARCHAR(200)  | NOT NULL           | Nama barang                   |
| 4   | kategori_id       | BIGINT        | FK → kategori      | Kategori barang               |
| 5   | lokasi_id         | BIGINT        | FK → lokasi        | Lokasi penyimpanan            |
| 6   | jumlah_stok       | INT           | DEFAULT(0)         | Jumlah stok saat ini          |
| 7   | reorder_point     | INT           | DEFAULT(10)        | Batas minimal stok            |
| 8   | satuan            | VARCHAR(50)   | DEFAULT('pcs')     | Satuan barang (pcs, box, etc) |
| 9   | status            | ENUM          | DEFAULT('baik')    | Status: baik, rusak, hilang   |
| 10  | deskripsi         | TEXT          | NULLABLE           | Deskripsi detail barang       |
| 11  | harga_satuan      | DECIMAL(15,2) | NULLABLE           | Harga per satuan              |
| 12  | merk              | VARCHAR(100)  | NULLABLE           | Merek barang                  |
| 13  | tanggal_pembelian | DATE          | NULLABLE           | Tanggal pembelian barang      |
| 14  | created_at        | TIMESTAMP     | NOT NULL           | Waktu pembuatan record        |
| 15  | updated_at        | TIMESTAMP     | NOT NULL           | Waktu update record           |
| 16  | deleted_at        | TIMESTAMP     | NULLABLE           | Soft delete timestamp         |

**Index:**

-   (kode_barang, nama_barang) - untuk pencarian
-   status - untuk filter status
-   jumlah_stok - untuk monitoring stok

**Relasi:**

-   BelongsTo → Kategori (kategori_id)
-   BelongsTo → Lokasi (lokasi_id)
-   HasMany → TransaksiBarang (barang_id)
-   HasMany → BarangRusak (barang_id)

---

## 5. Tabel Barang Rusak

**Penjelasan:** Tabel pencatatan barang rusak memtrack aset yang mengalami kerusakan dengan detail tanggal, jumlah, dan penanggung jawab. Data ini penting untuk evaluasi kondisi inventaris dan pertanggungjawaban pengguna.

**Nama Tabel:** `barang_rusaks`

| No. | Nama Field       | Tipe Data    | Constraint         | Penjelasan                    |
| --- | ---------------- | ------------ | ------------------ | ----------------------------- |
| 1   | id               | BIGINT       | PK, AUTO_INCREMENT | ID unik record kerusakan      |
| 2   | barang_id        | BIGINT       | FK → barang        | Barang yang rusak             |
| 3   | jumlah           | INT          | NOT NULL           | Jumlah barang rusak           |
| 4   | tanggal_kejadian | DATE         | NOT NULL           | Tanggal kerusakan terjadi     |
| 5   | keterangan       | TEXT         | NULLABLE           | Penjelasan penyebab kerusakan |
| 6   | penanggung_jawab | VARCHAR(100) | NOT NULL           | Nama penanggung jawab         |
| 7   | user_id          | BIGINT       | FK → users         | Pengguna yang melaporkan      |
| 8   | created_at       | TIMESTAMP    | NOT NULL           | Waktu pembuatan record        |
| 9   | updated_at       | TIMESTAMP    | NOT NULL           | Waktu update record           |

**Index:**

-   barang_id - untuk pencarian barang rusak
-   tanggal_kejadian - untuk laporan berdasarkan periode

**Relasi:**

-   BelongsTo → Barang (barang_id)
-   BelongsTo → User (user_id)

---

## 6. Tabel Transaksi Barang

**Penjelasan:** Tabel transaksi mencatat setiap pergerakan barang (masuk/keluar) dengan detail tanggal, jumlah, dan persetujuan. Sistem approval memastikan integritas data dan akuntabilitas setiap transaksi inventaris.

**Nama Tabel:** `transaksi_barang`

| No. | Nama Field        | Tipe Data    | Constraint         | Penjelasan                          |
| --- | ----------------- | ------------ | ------------------ | ----------------------------------- |
| 1   | id                | BIGINT       | PK, AUTO_INCREMENT | ID unik transaksi                   |
| 2   | kode_transaksi    | VARCHAR(50)  | UNIQUE, NOT NULL   | Kode referensi transaksi            |
| 3   | barang_id         | BIGINT       | FK → barang        | Barang yang ditransaksikan          |
| 4   | tipe_transaksi    | ENUM         | NOT NULL           | Tipe: masuk atau keluar             |
| 5   | jumlah            | INT          | NOT NULL           | Jumlah barang transaksi             |
| 6   | tanggal_transaksi | DATE         | NOT NULL           | Tanggal transaksi dilakukan         |
| 7   | penanggung_jawab  | VARCHAR(100) | NULLABLE           | Nama penanggung jawab               |
| 8   | keterangan        | TEXT         | NULLABLE           | Catatan transaksi                   |
| 9   | user_id           | BIGINT       | FK → users         | Pengguna pemohon transaksi          |
| 10  | approved_by       | BIGINT       | FK → users         | Pengguna yang menyetujui            |
| 11  | approved_at       | TIMESTAMP    | NULLABLE           | Waktu persetujuan                   |
| 12  | approval_status   | VARCHAR(20)  | -                  | Status: pending, approved, rejected |
| 13  | approval_notes    | TEXT         | NULLABLE           | Catatan persetujuan                 |
| 14  | created_at        | TIMESTAMP    | NOT NULL           | Waktu pembuatan record              |
| 15  | updated_at        | TIMESTAMP    | NOT NULL           | Waktu update record                 |

**Index:**

-   (barang_id, tipe_transaksi) - untuk laporan per barang
-   tanggal_transaksi - untuk filter periode
-   kode_transaksi - untuk pencarian transaksi

**Relasi:**

-   BelongsTo → Barang (barang_id)
-   BelongsTo → User (user_id) - pemohon
-   BelongsTo → User (approved_by) - penyetuju

---

## 7. Tabel Log Aktivitas

**Penjelasan:** Tabel audit log mencatat semua aktivitas pengguna (login, create, update, delete) dengan IP address dan user agent. Log ini mendukung monitoring keamanan dan tracing perubahan data untuk compliance audit.

**Nama Tabel:** `log_aktivitas`

| No. | Nama Field      | Tipe Data    | Constraint         | Penjelasan                                        |
| --- | --------------- | ------------ | ------------------ | ------------------------------------------------- |
| 1   | id              | BIGINT       | PK, AUTO_INCREMENT | ID unik log                                       |
| 2   | user_id         | BIGINT       | FK → users         | Pengguna yang melakukan aktivitas                 |
| 3   | jenis_aktivitas | ENUM         | NOT NULL           | Tipe: login, logout, create, update, delete, view |
| 4   | nama_tabel      | VARCHAR(100) | NULLABLE           | Nama tabel yang terpengaruh                       |
| 5   | record_id       | BIGINT       | NULLABLE           | ID record yang berubah                            |
| 6   | deskripsi       | TEXT         | NOT NULL           | Penjelasan aktivitas                              |
| 7   | perubahan_data  | JSON         | NULLABLE           | Data perubahan dalam format JSON                  |
| 8   | ip_address      | VARCHAR(45)  | NULLABLE           | IP address pengguna                               |
| 9   | user_agent      | TEXT         | NULLABLE           | Browser/device user agent string                  |
| 10  | created_at      | TIMESTAMP    | NOT NULL           | Waktu aktivitas terjadi                           |
| 11  | updated_at      | TIMESTAMP    | NOT NULL           | Waktu update record                               |

**Index:**

-   (user_id, jenis_aktivitas, created_at) - untuk analisis pengguna
-   (nama_tabel, record_id) - untuk tracing perubahan record
-   created_at - untuk filter periode

**Relasi:**

-   BelongsTo → User (user_id)

---

## 8. Tabel Backup Logs

**Penjelasan:** Tabel backup logs mencatat setiap operasi backup database dengan detail file, ukuran, dan status. Data ini membantu administrator mengelola backup retention dan memverifikasi kesuksesan backup rutin.

**Nama Tabel:** `backup_logs`

| No. | Nama Field | Tipe Data    | Constraint         | Penjelasan                       |
| --- | ---------- | ------------ | ------------------ | -------------------------------- |
| 1   | id         | BIGINT       | PK, AUTO_INCREMENT | ID unik backup log               |
| 2   | user_id    | BIGINT       | FK → users         | Pengguna yang membuat backup     |
| 3   | filename   | VARCHAR(255) | NULLABLE           | Nama file backup                 |
| 4   | format     | VARCHAR(50)  | DEFAULT('sql')     | Format: sql, json, dll           |
| 5   | file_size  | BIGINT       | NULLABLE           | Ukuran file dalam bytes          |
| 6   | status     | ENUM         | DEFAULT('pending') | Status: success, failed, pending |
| 7   | notes      | TEXT         | NULLABLE           | Catatan/pesan error              |
| 8   | created_at | TIMESTAMP    | NOT NULL           | Waktu backup dibuat              |
| 9   | updated_at | TIMESTAMP    | NOT NULL           | Waktu update record              |
| 10  | deleted_at | TIMESTAMP    | NULLABLE           | Soft delete timestamp            |

**Relasi:**

-   BelongsTo → User (user_id)

---

## Urutan Implementasi (Migrasi)

> ⚠️ **PENTING:** Ikuti urutan berikut saat membuat migrations untuk menghindari foreign key error:

1. ✅ **Users** - Tidak bergantung pada tabel lain
2. ✅ **Kategori** - Tidak bergantung pada tabel lain
3. ✅ **Lokasi** - Tidak bergantung pada tabel lain
4. ✅ **Barang** - Bergantung pada Kategori dan Lokasi
5. ✅ **Barang Rusak** - Bergantung pada Barang dan Users
6. ✅ **Transaksi Barang** - Bergantung pada Barang dan Users
7. ✅ **Log Aktivitas** - Bergantung pada Users
8. ✅ **Backup Logs** - Bergantung pada Users

---

## Entity Relationship Diagram (ERD)

```
┌─────────┐         ┌──────────────┐
│  Users  │◄────┐   │  Kategori    │
└─────────┘     │   └──────────────┘
     ▲          │
     │          │
     │          ├──────► ┌──────────┐
     │          │        │  Barang  │◄──────┐
     │          │        └──────────┘       │
     │          │             ▲             │
     │          └─────────────┘             │
     │                                      │
     │    ┌─────────────────────────────────┴─────┐
     │    │                                        │
     │    ├──► ┌──────────────┐                   │
     │    │    │ Barang Rusak │                   │
     │    │    └──────────────┘                   │
     │    │                                        │
     │    └──► ┌──────────────────┐              │
     │         │ Transaksi Barang │              │
     │         └──────────────────┘              │
     │                                            │
     ├──────► ┌──────────────┐                  │
     │        │ Log Aktivitas│                  │
     │        └──────────────┘                  │
     │                                           │
     └──────► ┌──────────────┐                 │
              │ Backup Logs  │                 │
              └──────────────┘                 │
                                                │
┌─────────┐                                     │
│  Lokasi │◄────────────────────────────────────┘
└─────────┘
```

---

## Modul Utama Aplikasi

Sistem Inventaris terdiri dari 6 modul utama yang terintegrasi dengan database. Setiap modul menyediakan fungsionalitas CRUD lengkap dengan tabel interaktif, pencarian, sorting, dan fitur bulk action.

### 1. Data Barang

**Deskripsi:** Modul ini menampilkan daftar seluruh barang inventaris dengan informasi lengkap. Pengguna dapat melihat kode barang, nama, kategori, jumlah stok, lokasi penyimpanan, dan status kondisi barang. Sistem memberikan penanda visual berupa warna status: hijau untuk "baik", kuning untuk "rusak", dan merah untuk "hilang". Fitur pencarian dan pengurutan memudahkan pengguna menemukan barang spesifik, serta memungkinkan pengeditan data langsung melalui tabel.

**Kolom Tabel:**

-   **Kode** (id) - Kode identitas barang, dapat dicari dan diurutkan
-   **Nama Barang** - Nama lengkap barang, dapat dicari
-   **Kategori** - Jenis/klasifikasi barang
-   **Jumlah** - Stok barang saat ini, dapat diurutkan
-   **Lokasi** - Tempat penyimpanan barang
-   **Status** - Kondisi barang (baik/rusak/hilang), visual badge berwarna

**Implementasi Tabel:**

```php
public static function table(Table $table): Table
{
    return $table->columns([
        Tables\Columns\TextColumn::make('kode_barang')
            ->label('Kode')
            ->searchable()
            ->sortable(),

        Tables\Columns\TextColumn::make('nama_barang')
            ->label('Nama Barang')
            ->searchable(),

        Tables\Columns\TextColumn::make('kategori.nama_kategori')
            ->label('Kategori'),

        Tables\Columns\TextColumn::make('jumlah_stok')
            ->label('Jumlah')
            ->sortable(),

        Tables\Columns\TextColumn::make('lokasi.nama_lokasi')
            ->label('Lokasi'),

        Tables\Columns\BadgeColumn::make('status')
            ->label('Status')
            ->colors([
                'success' => 'baik',
                'warning' => 'rusak',
                'danger' => 'hilang',
            ])
            ->sortable(),
    ])
    ->filters([])
    ->actions([
        Tables\Actions\EditAction::make(),
    ])
    ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
            Tables\Actions\DeleteBulkAction::make(),
        ]),
    ])
    ->defaultSort('nama_barang');
}
```

---

### 2. Transaksi Barang

**Deskripsi:** Fitur ini digunakan untuk mencatat dan menampilkan riwayat seluruh transaksi barang masuk maupun keluar. Setiap transaksi dilengkapi dengan ID unik, referensi barang, tanggal pelaksanaan, jumlah barang, dan keterangan detail. Status transaksi ditandai dengan badge berwarna (hijau untuk masuk, merah untuk keluar) sehingga mudah dibedakan. Data dapat diurutkan berdasarkan tanggal terbaru, diedit untuk koreksi, atau dihapus jika diperlukan.

**Kolom Tabel:**

-   **ID Transaksi** (kode_transaksi) - Nomor referensi unik transaksi, dapat dicari dan diurutkan
-   **Barang** (barang.nama_barang) - Nama barang yang ditransaksikan, dapat dicari
-   **Tanggal** - Tanggal pelaksanaan transaksi, format date, dapat diurutkan
-   **Jumlah** - Banyaknya barang dalam transaksi, dapat diurutkan
-   **Jenis** (tipe_transaksi) - Tipe transaksi: masuk atau keluar, visual badge
-   **Keterangan** - Catatan tambahan transaksi

**Implementasi Tabel:**

```php
public static function table(Table $table): Table
{
    return $table->columns([
        Tables\Columns\TextColumn::make('kode_transaksi')
            ->label('ID')
            ->searchable()
            ->sortable(),

        Tables\Columns\TextColumn::make('barang.nama_barang')
            ->label('Barang')
            ->searchable(),

        Tables\Columns\TextColumn::make('tanggal_transaksi')
            ->label('Tanggal')
            ->date()
            ->sortable(),

        Tables\Columns\TextColumn::make('jumlah')
            ->label('Jumlah')
            ->sortable(),

        Tables\Columns\BadgeColumn::make('tipe_transaksi')
            ->label('Jenis')
            ->colors([
                'success' => 'masuk',
                'danger' => 'keluar',
            ])
            ->formatStateUsing(fn($state) => ucfirst($state)),

        Tables\Columns\TextColumn::make('keterangan')
            ->label('Keterangan')
            ->limit(30)
            ->wrap(),
    ])
    ->filters([])
    ->actions([
        Tables\Actions\EditAction::make(),
    ])
    ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
            Tables\Actions\DeleteBulkAction::make(),
        ]),
    ])
    ->defaultSort('tanggal_transaksi', 'desc');
}
```

---

### 3. Log Aktivitas

**Deskripsi:** Modul audit log mencatat semua tindakan pengguna dalam sistem seperti login, pembuatan data, pengeditan, atau penghapusan. Sistem menyimpan informasi detail meliputi pengguna yang melakukan aksi, jenis aktivitas, waktu pelaksanaan, tabel yang terpengaruh, dan deskripsi perubahan data. Log ini mendukung monitoring keamanan sistem, tracing perubahan data untuk compliance audit, dan membantu dalam investigasi jika terjadi kesalahan atau aktivitas mencurigakan. Log ditampilkan dalam urutan terbaru terlebih dahulu dan hanya dapat dilihat (read-only).

**Kolom Tabel:**

-   **Pengguna** (user.name) - Nama pengguna yang melakukan aktivitas, dapat dicari
-   **Aksi** (jenis_aktivitas) - Jenis aktivitas: login, logout, create, update, delete, view
-   **Tabel** (nama_tabel) - Nama tabel yang terpengaruh oleh aktivitas
-   **Waktu** (created_at) - Waktu aktivitas terjadi dengan format lengkap, dapat diurutkan

**Implementasi Tabel:**

```php
public static function table(Table $table): Table
{
    return $table->columns([
        Tables\Columns\TextColumn::make('user.name')
            ->label('Pengguna')
            ->searchable(),

        Tables\Columns\TextColumn::make('jenis_aktivitas')
            ->label('Aksi')
            ->formatStateUsing(fn($state) => ucfirst(str_replace('_', ' ', $state)))
            ->searchable(),

        Tables\Columns\TextColumn::make('nama_tabel')
            ->label('Tabel')
            ->searchable(),

        Tables\Columns\TextColumn::make('created_at')
            ->label('Waktu')
            ->dateTime('d M Y, H:i:s')
            ->sortable(),
    ])
    ->filters([])
    ->actions([]) // Read-only, tidak ada edit/delete
    ->bulkActions([]) // Tidak ada bulk action
    ->defaultSort('created_at', 'desc');
}
```

---

### 4. Dashboard Statistik

**Deskripsi:** Dashboard berfungsi sebagai landing page dan ringkasan statistik utama sistem inventaris. Tampilan dirancang untuk memberikan overview cepat kepada pengguna tentang kondisi inventaris secara keseluruhan. Informasi yang disajikan mencakup jumlah total barang aktif dalam sistem, jumlah transaksi barang masuk, dan jumlah transaksi barang keluar. Setiap statistik ditampilkan dalam bentuk kartu (card) yang dilengkapi dengan ikon visual, deskripsi singkat, dan penanda warna untuk membedakan jenis data dan memudahkan interpretasi.

**Kartu Statistik:**

-   **Total Barang** - Jumlah seluruh barang inventaris yang terdaftar di sistem
-   **Barang Masuk** - Jumlah transaksi barang yang masuk ke gudang, ditandai warna hijau (success)
-   **Barang Keluar** - Jumlah transaksi barang yang keluar dari gudang, ditandai warna merah (danger)

**Implementasi Widget:**

```php
class StatsOverviewWidget extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total Barang', Barang::count())
                ->description('Data inventaris aktif')
                ->icon('heroicon-o-archive-box'),

            Card::make('Barang Masuk', TransaksiBarang::where('tipe_transaksi', 'masuk')->count())
                ->description('Transaksi masuk')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success'),

            Card::make('Barang Keluar', TransaksiBarang::where('tipe_transaksi', 'keluar')->count())
                ->description('Transaksi keluar')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('danger'),
        ];
    }
}
```

---

### 5. Manajemen Pengguna

**Deskripsi:** Modul User digunakan oleh administrator untuk mengelola akun pengguna sistem secara terpusat. Admin dapat menambah pengguna baru, mengubah data pengguna (nama dan email), serta menghapus akun yang tidak diperlukan. Setiap pengguna memiliki role/peran yang menentukan level akses dan izin pada fitur tertentu. Role ditampilkan menggunakan badge berwarna berbeda (hijau untuk admin, biru untuk petugas inventaris, kuning untuk kepala sekolah) untuk memudahkan identifikasi. Sistem juga mendukung pencarian pengguna berdasarkan nama atau email.

**Kolom Tabel:**

-   **Nama** (name) - Nama lengkap pengguna, dapat dicari
-   **Email** (email) - Alamat email pengguna, dapat dicari
-   **Role** (role via Spatie Permission) - Peran pengguna dengan visual badge berwarna berbeda

**Implementasi Tabel:**

```php
public static function table(Table $table): Table
{
    return $table->columns([
        Tables\Columns\TextColumn::make('name')
            ->label('Nama')
            ->searchable(),

        Tables\Columns\TextColumn::make('email')
            ->label('Email')
            ->searchable(),

        Tables\Columns\TextColumn::make('roles.name')
            ->label('Role')
            ->badge()
            ->colors([
                'success' => 'admin',
                'info' => 'petugas_inventaris',
                'warning' => 'kepala_sekolah',
            ])
            ->formatStateUsing(fn($state) => ucwords(str_replace('_', ' ', $state))),
    ])
    ->filters([])
    ->actions([
        Tables\Actions\EditAction::make(),
    ])
    ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
            Tables\Actions\DeleteBulkAction::make(),
        ]),
    ])
    ->defaultSort('name');
}
```

---

### 6. Backup & Restore Database

**Deskripsi:** Modul backup memfasilitasi administrator dalam membuat backup database dan melakukan restore ketika diperlukan. Fitur ini penting untuk disaster recovery dan business continuity. Admin dapat membuat backup baru dengan sekali klik, melihat riwayat semua backup yang telah dibuat (dengan nama file dan ukuran), serta melakukan restore dari file backup lama. Sistem mencatat siapa yang membuat backup, kapan waktu pembuatan, status proses (success/failed), dan pesan error jika terjadi kegagalan. Backup file disimpan dengan format .sql di directory storage/backups.

**Kolom Tabel Backup Logs:**

-   **Nama File** (filename) - Nama file backup yang dibuat
-   **Ukuran** (file_size) - Ukuran file dalam bytes, ditampilkan dalam format human-readable
-   **Format** (format) - Format backup (SQL, JSON, dll)
-   **Status** - Status proses backup: success (hijau), failed (merah), pending (abu-abu)
-   **Tanggal** (created_at) - Waktu backup dibuat
-   **Dibuat Oleh** (user.name) - Nama pengguna yang membuat backup

**Aksi:**

-   **Buat Backup Baru** - Tombol di header untuk membuat backup database baru
-   **Restore Database** - Tombol di header untuk upload file backup dan restore database
-   **Download** - Tombol per-row untuk mengunduh file backup
-   **Hapus** - Tombol per-row untuk menghapus record backup dari log

**Implementasi Tabel:**

```php
public static function table(Table $table): Table
{
    return $table->columns([
        Tables\Columns\TextColumn::make('filename')
            ->label('Nama File')
            ->searchable()
            ->sortable(),

        Tables\Columns\TextColumn::make('file_size')
            ->label('Ukuran')
            ->formatStateUsing(fn($state) => $state ? number_format($state / 1024, 2) . ' KB' : '-')
            ->sortable(),

        Tables\Columns\TextColumn::make('format')
            ->label('Format')
            ->badge()
            ->colors([
                'info' => 'sql',
                'warning' => 'json',
            ]),

        Tables\Columns\BadgeColumn::make('status')
            ->label('Status')
            ->colors([
                'success' => 'success',
                'danger' => 'failed',
                'warning' => 'pending',
            ])
            ->formatStateUsing(fn($state) => ucfirst($state))
            ->sortable(),

        Tables\Columns\TextColumn::make('created_at')
            ->label('Tanggal')
            ->dateTime('d M Y, H:i')
            ->sortable(),

        Tables\Columns\TextColumn::make('user.name')
            ->label('Dibuat Oleh')
            ->searchable(),
    ])
    ->filters([])
    ->actions([
        Tables\Actions\Action::make('download')
            ->label('Download')
            ->icon('heroicon-o-arrow-down-tray')
            ->action(fn($record) => response()->download(
                storage_path('backups/' . $record->filename),
                $record->filename
            ))
            ->visible(fn($record) => $record->status === 'success' && file_exists(storage_path('backups/' . $record->filename))),

        Tables\Actions\DeleteAction::make(),
    ])
    ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
            Tables\Actions\DeleteBulkAction::make(),
        ]),
    ])
    ->defaultSort('created_at', 'desc');
}
```

**Implementasi Header Actions (Buat Backup & Restore):**

```php
protected function getHeaderActions(): array
{
    return [
        Action::make('createBackup')
            ->label('Buat Backup Baru')
            ->icon('heroicon-o-plus')
            ->color('success')
            ->action(fn () => $this->createBackup())
            ->requiresConfirmation()
            ->modalHeading('Buat Backup Baru')
            ->modalDescription('Ini akan membuat backup database baru. Proses ini mungkin memerlukan beberapa menit untuk database besar.')
            ->modalSubmitActionLabel('Ya, Buat Backup'),

        Action::make('restoreDatabase')
            ->label('Restore Database')
            ->icon('heroicon-o-arrow-path')
            ->color('danger')
            ->visible(fn () => Auth::user()?->hasPermissionTo('restore_system') ?? false)
            ->form([
                FileUpload::make('sql_file')
                    ->label('Upload File Backup (.sql)')
                    ->maxSize(102400)
                    ->required()
                    ->rules([
                        function () {
                            return function (string $attribute, $value, \Closure $fail) {
                                $extension = strtolower(pathinfo($value->getClientOriginalName(), PATHINFO_EXTENSION));
                                if ($extension !== 'sql') {
                                    $fail('File harus berformat .sql');
                                }
                            };
                        },
                    ])
                    ->helperText('Pilih file backup .sql yang ingin direstore. Proses ini akan menghapus semua data yang ada!')
                    ->disk('local')
                    ->directory('temp-restores')
                    ->visibility('private')
                    ->storeFiles(false),
            ])
            ->action(function (array $data) {
                $uploadedFile = $data['sql_file'];
                if (is_array($uploadedFile)) {
                    $uploadedFile = $uploadedFile[0];
                }
                $this->restoreFromUpload($uploadedFile);
            })
            ->requiresConfirmation()
            ->modalHeading('Restore Database dari File')
            ->modalDescription('⚠️ PERINGATAN: Proses ini akan menghapus SEMUA data yang ada di database dan menggantinya dengan data dari file backup. Pastikan Anda sudah membuat backup terlebih dahulu!')
            ->modalSubmitActionLabel('Ya, Restore Database')
            ->modalIcon('heroicon-o-exclamation-triangle'),
    ];
}

public function createBackup(): void
{
    try {
        $backupLog = BackupLog::create([
            'user_id' => Auth::id(),
            'status' => 'pending',
            'format' => 'sql',
        ]);

        \Illuminate\Support\Facades\Artisan::call('db:backup');

        $backupDir = storage_path('backups');
        $files = \Illuminate\Support\Facades\File::files($backupDir);

        if (empty($files)) {
            throw new \Exception('Tidak ada file backup yang ditemukan.');
        }

        usort($files, function ($a, $b) {
            return $b->getMTime() - $a->getMTime();
        });

        $latestFile = $files[0];
        $filename = basename($latestFile->getPathname());
        $fileSize = $latestFile->getSize();

        $backupLog->update([
            'filename' => $filename,
            'file_size' => $fileSize,
            'status' => 'success',
        ]);

        Notification::make()
            ->title('Backup Berhasil')
            ->body("Database berhasil di-backup: {$filename}")
            ->success()
            ->send();

        $this->loadBackups();
    } catch (\Exception $e) {
        if (isset($backupLog)) {
            $backupLog->update([
                'status' => 'failed',
                'notes' => $e->getMessage(),
            ]);
        }

        Notification::make()
            ->title('Backup Gagal')
            ->body($e->getMessage())
            ->danger()
            ->send();
    }
}

public function restoreFromUpload($uploadedFile): void
{
    try {
        if (! Auth::user()->hasPermissionTo('restore_system')) {
            throw new \Exception('Anda tidak memiliki izin untuk restore database.');
        }

        if (is_string($uploadedFile)) {
            $tempPath = $uploadedFile;
            $originalName = basename($uploadedFile);
        } else {
            $tempPath = $uploadedFile->getRealPath();
            $originalName = $uploadedFile->getClientOriginalName();
        }

        if (! \Illuminate\Support\Facades\File::exists($tempPath)) {
            throw new \Exception('File backup tidak ditemukan.');
        }

        $fileSize = \Illuminate\Support\Facades\File::size($tempPath);
        $userId = Auth::id();

        $sql = \Illuminate\Support\Facades\File::get($tempPath);

        if (empty(trim($sql))) {
            throw new \Exception('File backup kosong atau tidak valid.');
        }

        \Illuminate\Support\Facades\DB::unprepared('SET FOREIGN_KEY_CHECKS=0');
        \Illuminate\Support\Facades\DB::unprepared($sql);
        \Illuminate\Support\Facades\DB::unprepared('SET FOREIGN_KEY_CHECKS=1');

        BackupLog::create([
            'user_id' => $userId,
            'filename' => $originalName,
            'file_size' => $fileSize,
            'format' => 'sql',
            'status' => 'success',
            'notes' => 'Restore berhasil dari file upload: '.$originalName,
        ]);

        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');

        Notification::make()
            ->title('Restore Berhasil')
            ->body('Database berhasil di-restore dari file: '.$originalName)
            ->success()
            ->duration(3000)
            ->send();

        $this->loadBackups();

        $this->redirect(route('filament.admin.pages.backup-manager'), navigate: false);

    } catch (\Exception $e) {
        try {
            \Illuminate\Support\Facades\DB::unprepared('SET FOREIGN_KEY_CHECKS=1');
        } catch (\Exception $ignored) {
        }

        Notification::make()
            ->title('Restore Gagal')
            ->body('Error: '.$e->getMessage())
            ->danger()
            ->duration(10000)
            ->send();
    }
}
```

---

**Dokumentasi dibuat:** 28 Desember 2025  
**Versi Database:** Laravel 12.43.1  
**Database Engine:** MySQL
