# Skenario Pengujian Black Box - Sistem Inventaris Sekolah

Pengujian black box dilakukan pada beberapa fitur utama Sistem Inventaris Sekolah tanpa mempertimbangkan logika internal kode. Pengujian berfokus pada input, proses, dan output sistem. Setiap skenario pengujian dilakukan dengan memasukkan data uji tertentu dan membandingkan hasil yang diharapkan dengan hasil yang diperoleh dari sistem.

---

## 1. FITUR LOGIN (AUTHENTIKASI)

| No   | Fitur | Skenario Uji                     | Input                                                    | Output yang Diharapkan                         | Hasil    |
| ---- | ----- | -------------------------------- | -------------------------------------------------------- | ---------------------------------------------- | -------- |
| 1.1  | Login | Input valid (Admin Sistem)       | Email: admin@inventaris.test, Password: password         | Berhasil masuk ke dashboard admin              | ✓ Sesuai |
| 1.2  | Login | Input valid (Petugas Inventaris) | Email: petugas@inventaris.test, Password: password       | Berhasil masuk ke dashboard petugas            | ✓ Sesuai |
| 1.3  | Login | Input valid (Kepala Sekolah)     | Email: kepala@inventaris.test, Password: password        | Berhasil masuk ke dashboard kepala sekolah     | ✓ Sesuai |
| 1.4  | Login | Password salah                   | Email: admin@inventaris.test, Password: wrongpassword    | Sistem menolak login, menampilkan pesan error  | ✓ Sesuai |
| 1.5  | Login | Email tidak terdaftar            | Email: notregistered@inventaris.test, Password: password | Sistem menolak login, menampilkan pesan error  | ✓ Sesuai |
| 1.6  | Login | Email kosong                     | Email: (kosong), Password: password                      | Sistem menampilkan validasi error              | ✓ Sesuai |
| 1.7  | Login | Format email tidak valid         | Email: invalidemail, Password: password                  | Sistem menampilkan validasi error format email | ✓ Sesuai |
| 1.8  | Login | User tidak aktif                 | Email: inactive@inventaris.test (is_active=false)        | Sistem menolak login, user tidak aktif         | ✓ Sesuai |
| 1.9  | Login | Akses dashboard tanpa login      | URL: /admin (tanpa login)                                | Sistem redirect ke halaman login               | ✓ Sesuai |
| 1.10 | Login | Akses resource tanpa permission  | Petugas akses User Management                            | Sistem menolak akses (403 Forbidden)           | ✓ Sesuai |
| 1.11 | Login | Admin dapat akses semua menu     | Login sebagai Admin Sistem                               | Semua menu terlihat dan dapat diakses          | ✓ Sesuai |

---

## 2. FITUR MANAJEMEN BARANG

| No   | Fitur  | Skenario Uji                     | Input                                                          | Output yang Diharapkan                                   | Hasil    |
| ---- | ------ | -------------------------------- | -------------------------------------------------------------- | -------------------------------------------------------- | -------- |
| 2.1  | Barang | Tambah barang baru lengkap       | Nama: Meja Guru, Kategori: Furniture, Lokasi: R.Guru, Stok: 10 | Barang berhasil ditambahkan, kode_barang auto-generate   | ✓ Sesuai |
| 2.2  | Barang | Tambah barang dengan kode custom | Kode: FRN-MJ-001, Nama: Meja Siswa, Stok: 50                   | Barang ditambahkan dengan kode custom                    | ✓ Sesuai |
| 2.3  | Barang | Kode barang auto-generate        | Nama: Papan Tulis, Kategori: Alat Tulis, Lokasi: R.Kelas       | Kode: AT-RK-001 (generate otomatis dari kategori+lokasi) | ✓ Sesuai |
| 2.4  | Barang | Update nama barang               | Nama lama: Meja Kayu, Nama baru: Meja Kayu Jati                | Nama barang diupdate, log aktivitas tercatat             | ✓ Sesuai |
| 2.5  | Barang | Update stok barang               | Stok lama: 10, Stok baru: 15                                   | Stok diupdate, perubahan tercatat di log                 | ✓ Sesuai |
| 2.6  | Barang | Nama barang kosong               | Nama: (kosong), Kategori: Furniture                            | Sistem menampilkan validasi error                        | ✓ Sesuai |
| 2.7  | Barang | Stok negatif                     | Stok: -5                                                       | Sistem menampilkan validasi error                        | ✓ Sesuai |
| 2.8  | Barang | Kode barang duplikat             | Kode: FRN-MJ-001 (sudah ada)                                   | Sistem menampilkan pesan "Kode barang sudah ada"         | ✓ Sesuai |
| 2.9  | Barang | Hapus barang (soft delete)       | Barang: Meja Guru #1                                           | Barang dihapus (soft delete), masih bisa direstore       | ✓ Sesuai |
| 2.10 | Barang | Restore barang yang dihapus      | Barang: Meja Guru #1                                           | Barang dikembalikan, status aktif kembali                | ✓ Sesuai |

---

## 3. FITUR MANAJEMEN KATEGORI

| No  | Fitur    | Skenario Uji               | Input                                                  | Output yang Diharapkan                             | Hasil    |
| --- | -------- | -------------------------- | ------------------------------------------------------ | -------------------------------------------------- | -------- |
| 3.1 | Kategori | Tambah kategori baru       | Nama: Elektronik, Kode: ELK, Deskripsi: "..."          | Kategori berhasil ditambahkan                      | ✓ Sesuai |
| 3.2 | Kategori | Tambah kategori tanpa kode | Nama: Alat Olahraga, Kode: (kosong)                    | Sistem menampilkan validasi error                  | ✓ Sesuai |
| 3.3 | Kategori | Update nama kategori       | Nama lama: Elektronik, Nama baru: Peralatan Elektronik | Nama kategori diupdate                             | ✓ Sesuai |
| 3.4 | Kategori | Hapus kategori kosong      | Kategori: Alat Lab (tidak ada barang)                  | Kategori berhasil dihapus                          | ✓ Sesuai |
| 3.5 | Kategori | Hapus kategori berisi      | Kategori: Furniture (ada 10 barang)                    | Sistem menolak, tampilkan pesan error              | ✓ Sesuai |
| 3.6 | Kategori | Nama kategori kosong       | Nama: (kosong), Kode: ELK                              | Sistem menampilkan validasi error                  | ✓ Sesuai |
| 3.7 | Kategori | Kode kategori duplikat     | Kode: FRN (sudah ada)                                  | Sistem menampilkan pesan "Kode kategori sudah ada" | ✓ Sesuai |

---

## 4. FITUR MANAJEMEN LOKASI

| No  | Fitur  | Skenario Uji             | Input                                                 | Output yang Diharapkan                           | Hasil    |
| --- | ------ | ------------------------ | ----------------------------------------------------- | ------------------------------------------------ | -------- |
| 4.1 | Lokasi | Tambah lokasi baru       | Nama: Ruang Guru, Kode: RG, Deskripsi: "..."          | Lokasi berhasil ditambahkan                      | ✓ Sesuai |
| 4.2 | Lokasi | Tambah lokasi tanpa kode | Nama: Laboratorium Komputer, Kode: (kosong)           | Sistem menampilkan validasi error                | ✓ Sesuai |
| 4.3 | Lokasi | Update nama lokasi       | Nama lama: Ruang Guru, Nama baru: Ruang Guru Lantai 2 | Nama lokasi diupdate                             | ✓ Sesuai |
| 4.4 | Lokasi | Hapus lokasi kosong      | Lokasi: Gudang Baru (tidak ada barang)                | Lokasi berhasil dihapus                          | ✓ Sesuai |
| 4.5 | Lokasi | Hapus lokasi berisi      | Lokasi: Ruang Kelas (ada 30 barang)                   | Sistem menolak, tampilkan pesan error            | ✓ Sesuai |
| 4.6 | Lokasi | Nama lokasi kosong       | Nama: (kosong), Kode: RG                              | Sistem menampilkan validasi error                | ✓ Sesuai |
| 4.7 | Lokasi | Kode lokasi duplikat     | Kode: RG (sudah ada)                                  | Sistem menampilkan pesan "Kode lokasi sudah ada" | ✓ Sesuai |

---

## 5. FITUR TRANSAKSI BARANG (MASUK & KELUAR)

| No   | Fitur     | Skenario Uji                   | Input                                                  | Output yang Diharapkan                          | Hasil    |
| ---- | --------- | ------------------------------ | ------------------------------------------------------ | ----------------------------------------------- | -------- |
| 5.1  | Transaksi | Transaksi masuk barang baru    | Barang: Meja, Qty: 10, Jenis: Masuk, Sumber: Pembelian | Transaksi tercatat, stok bertambah 10           | ✓ Sesuai |
| 5.2  | Transaksi | Transaksi masuk dengan dokumen | Barang: Kursi, Qty: 5, No. Dokumen: PO-2024-001        | Transaksi tercatat dengan nomor dokumen         | ✓ Sesuai |
| 5.3  | Transaksi | Transaksi keluar normal        | Barang: Meja (stok: 50), Qty: 5, Jenis: Keluar         | Transaksi tercatat, stok berkurang 5            | ✓ Sesuai |
| 5.4  | Transaksi | Transaksi keluar melebihi stok | Barang: Kursi (stok: 3), Qty: 5, Jenis: Keluar         | Sistem menampilkan error "Stok tidak mencukupi" | ✓ Sesuai |
| 5.5  | Transaksi | Transaksi keluar stok = 0      | Barang: Proyektor (stok: 0), Qty: 1                    | Sistem menampilkan error "Stok tidak tersedia"  | ✓ Sesuai |
| 5.6  | Transaksi | Qty = 0                        | Barang: Meja, Qty: 0                                   | Sistem menampilkan validasi error               | ✓ Sesuai |
| 5.7  | Transaksi | Qty negatif                    | Barang: Kursi, Qty: -5                                 | Sistem menampilkan validasi error               | ✓ Sesuai |
| 5.8  | Transaksi | Approve transaksi masuk        | Transaksi: TRX-001, Status: Pending                    | Status berubah "Approved", stok bertambah       | ✓ Sesuai |
| 5.9  | Transaksi | Reject transaksi               | Transaksi: TRX-002, Status: Pending                    | Status berubah "Rejected", stok tidak berubah   | ✓ Sesuai |
| 5.10 | Transaksi | Non-admin tidak bisa approve   | User: Petugas, Action: Approve                         | Sistem menolak, tidak ada tombol approve        | ✓ Sesuai |

---

## 6. FITUR BARANG RUSAK

| No  | Fitur        | Skenario Uji                       | Input                                                         | Output yang Diharapkan                            | Hasil    |
| --- | ------------ | ---------------------------------- | ------------------------------------------------------------- | ------------------------------------------------- | -------- |
| 6.1 | Barang Rusak | Laporkan barang rusak              | Barang: Kursi #5, Qty Rusak: 2, Kondisi: Rusak Total          | Laporan tercatat, status "Dilaporkan"             | ✓ Sesuai |
| 6.2 | Barang Rusak | Laporan dengan deskripsi kerusakan | Barang: Laptop, Deskripsi: "Layar retak, tidak bisa menyala"  | Laporan tercatat dengan detail kerusakan          | ✓ Sesuai |
| 6.3 | Barang Rusak | Laporan barang rusak ringan        | Barang: Proyektor, Kondisi: Rusak Ringan, Estimasi: Rp500.000 | Laporan tercatat dengan estimasi perbaikan        | ✓ Sesuai |
| 6.4 | Barang Rusak | Update status "Dalam Perbaikan"    | Laporan: BR-001, Status: Dalam Perbaikan                      | Status diupdate, tanggal mulai perbaikan tercatat | ✓ Sesuai |
| 6.5 | Barang Rusak | Update status "Selesai"            | Laporan: BR-001, Status: Selesai                              | Status diupdate, barang kembali ke stok           | ✓ Sesuai |
| 6.6 | Barang Rusak | Update status "Dihapuskan"         | Laporan: BR-002, Status: Dihapuskan                           | Status diupdate, stok barang berkurang            | ✓ Sesuai |
| 6.7 | Barang Rusak | Qty rusak melebihi stok            | Barang: Kursi (stok: 5), Qty rusak: 10                        | Sistem menampilkan error "Qty melebihi stok"      | ✓ Sesuai |
| 6.8 | Barang Rusak | Qty rusak = 0                      | Qty rusak: 0                                                  | Sistem menampilkan validasi error                 | ✓ Sesuai |

---

## 7. FITUR USER MANAGEMENT (ADMIN)

| No   | Fitur | Skenario Uji                       | Input                                                               | Output yang Diharapkan                              | Hasil    |
| ---- | ----- | ---------------------------------- | ------------------------------------------------------------------- | --------------------------------------------------- | -------- |
| 7.1  | User  | Tambah user Admin Sistem           | Nama: Admin Baru, Email: admin2@inventaris.test, Role: Admin Sistem | User berhasil ditambahkan sebagai admin             | ✓ Sesuai |
| 7.2  | User  | Tambah user Petugas Inventaris     | Nama: Petugas Baru, Email: petugas2@inventaris.test, Role: Petugas  | User berhasil ditambahkan sebagai petugas           | ✓ Sesuai |
| 7.3  | User  | Tambah user Kepala Sekolah         | Nama: Kepala Baru, Email: kepala2@inventaris.test, Role: Kepala     | User berhasil ditambahkan sebagai kepala            | ✓ Sesuai |
| 7.4  | User  | Update nama user                   | Nama lama: Admin Lama, Nama baru: Admin Sistem Baru                 | Nama user diupdate                                  | ✓ Sesuai |
| 7.5  | User  | Update role user                   | Role lama: Petugas, Role baru: Admin Sistem                         | Role diupdate, permission berubah                   | ✓ Sesuai |
| 7.6  | User  | Nonaktifkan user                   | Status: Aktif → Tidak Aktif                                         | User tidak bisa login                               | ✓ Sesuai |
| 7.7  | User  | Email kosong                       | Nama: User Baru, Email: (kosong)                                    | Sistem menampilkan validasi error                   | ✓ Sesuai |
| 7.8  | User  | Email duplikat                     | Email: admin@inventaris.test (sudah ada)                            | Sistem menampilkan pesan "Email sudah terdaftar"    | ✓ Sesuai |
| 7.9  | User  | Password < 8 karakter              | Password: pass123                                                   | Sistem menampilkan error "Minimal 8 karakter"       | ✓ Sesuai |
| 7.10 | User  | Admin Sistem akses semua fitur     | Login: Admin Sistem                                                 | Dapat mengakses User, Kategori, Lokasi, Log, Backup | ✓ Sesuai |
| 7.11 | User  | Petugas tidak bisa akses User Mgmt | Login: Petugas, Akses: /admin/users                                 | Sistem menolak akses (403)                          | ✓ Sesuai |

---

## 8. FITUR LOG AKTIVITAS

| No  | Fitur         | Skenario Uji                   | Input                         | Output yang Diharapkan                           | Hasil    |
| --- | ------------- | ------------------------------ | ----------------------------- | ------------------------------------------------ | -------- |
| 8.1 | Log Aktivitas | Log saat tambah barang         | Barang baru: Meja Guru        | Log tercatat: "Menambahkan barang Meja Guru"     | ✓ Sesuai |
| 8.2 | Log Aktivitas | Log saat update barang         | Update stok Meja: 10 → 15     | Log tercatat: "Mengubah barang Meja Guru"        | ✓ Sesuai |
| 8.3 | Log Aktivitas | Log saat hapus barang          | Hapus barang: Kursi Rusak     | Log tercatat: "Menghapus barang Kursi Rusak"     | ✓ Sesuai |
| 8.4 | Log Aktivitas | Log saat transaksi masuk       | Transaksi masuk: 10 Meja      | Log tercatat: "Transaksi Masuk - Meja (10 unit)" | ✓ Sesuai |
| 8.5 | Log Aktivitas | Filter berdasarkan user        | User: Admin Sistem            | Menampilkan log aktivitas Admin Sistem saja      | ✓ Sesuai |
| 8.6 | Log Aktivitas | Filter berdasarkan tanggal     | Tanggal: 01-31 Januari 2025   | Menampilkan log bulan Januari 2025               | ✓ Sesuai |
| 8.7 | Log Aktivitas | Search dengan keyword          | Keyword: "Meja"               | Menampilkan log yang mengandung kata "Meja"      | ✓ Sesuai |
| 8.8 | Log Aktivitas | Admin hapus log lama           | Log: > 1 tahun yang lalu      | Log berhasil dihapus                             | ✓ Sesuai |
| 8.9 | Log Aktivitas | Non-admin tidak bisa hapus log | User: Petugas, Action: Delete | Tombol delete tidak muncul                       | ✓ Sesuai |

---

## 9. FITUR BACKUP & RESTORE

| No  | Fitur   | Skenario Uji                     | Input                               | Output yang Diharapkan                           | Hasil    |
| --- | ------- | -------------------------------- | ----------------------------------- | ------------------------------------------------ | -------- |
| 9.1 | Backup  | Buat backup manual               | Action: Create Backup               | Backup file dibuat, tersimpan di storage/backups | ✓ Sesuai |
| 9.2 | Backup  | Backup dengan timestamp          | Timestamp: 2025-01-15_120530        | File backup: backup_2025-01-15_120530.sql        | ✓ Sesuai |
| 9.3 | Backup  | View daftar backup               | Akses: Backup Manager               | Menampilkan daftar semua backup dengan detail    | ✓ Sesuai |
| 9.4 | Backup  | Download backup file             | Backup: backup_2025-01-15.sql       | File berhasil didownload                         | ✓ Sesuai |
| 9.5 | Restore | Restore dari backup              | File: backup_2025-01-15.sql         | Database direstore ke kondisi backup tersebut    | ✓ Sesuai |
| 9.6 | Restore | Restore dengan konfirmasi        | Konfirmasi: Ya                      | Restore dijalankan setelah konfirmasi            | ✓ Sesuai |
| 9.7 | Restore | Cancel restore                   | Konfirmasi: Tidak                   | Restore dibatalkan, data tidak berubah           | ✓ Sesuai |
| 9.8 | Backup  | Admin dapat akses backup         | User: Admin Sistem                  | Dapat akses halaman Backup Manager               | ✓ Sesuai |
| 9.9 | Backup  | Petugas tidak dapat akses backup | User: Petugas, Akses: /admin/backup | Sistem menolak akses (403)                       | ✓ Sesuai |

---

## 10. FITUR DASHBOARD & LAPORAN

| No   | Fitur     | Skenario Uji                  | Input                      | Output yang Diharapkan                                    | Hasil    |
| ---- | --------- | ----------------------------- | -------------------------- | --------------------------------------------------------- | -------- |
| 10.1 | Dashboard | View dashboard Admin          | Login: Admin Sistem        | Menampilkan statistik total barang, transaksi, user       | ✓ Sesuai |
| 10.2 | Dashboard | View dashboard Petugas        | Login: Petugas Inventaris  | Menampilkan statistik barang, transaksi hari ini          | ✓ Sesuai |
| 10.3 | Dashboard | View dashboard Kepala Sekolah | Login: Kepala Sekolah      | Menampilkan ringkasan, grafik, transaksi pending approval | ✓ Sesuai |
| 10.4 | Dashboard | Widget total barang           | -                          | Menampilkan total barang yang ada                         | ✓ Sesuai |
| 10.5 | Laporan   | Laporan stok barang           | Filter: Semua kategori     | Menampilkan daftar barang dengan stok                     | ✓ Sesuai |
| 10.6 | Laporan   | Laporan transaksi bulanan     | Bulan: Januari 2025        | Menampilkan semua transaksi bulan Januari                 | ✓ Sesuai |
| 10.7 | Laporan   | Laporan barang per kategori   | Kategori: Furniture        | Menampilkan barang kategori Furniture saja                | ✓ Sesuai |
| 10.8 | Laporan   | Export laporan ke Excel       | Laporan: Stok Barang       | File Excel berhasil didownload                            | ✓ Sesuai |
| 10.9 | Laporan   | Export laporan ke PDF         | Laporan: Transaksi Bulanan | File PDF berhasil didownload                              | ✓ Sesuai |

---

## 11. FITUR APPROVAL TRANSAKSI

| No   | Fitur    | Skenario Uji                  | Input                               | Output yang Diharapkan                       | Hasil    |
| ---- | -------- | ----------------------------- | ----------------------------------- | -------------------------------------------- | -------- |
| 11.1 | Approval | View daftar transaksi pending | User: Kepala Sekolah                | Menampilkan semua transaksi status "Pending" | ✓ Sesuai |
| 11.2 | Approval | Approve transaksi masuk       | Transaksi: TRX-001, Action: Approve | Status "Approved", stok barang bertambah     | ✓ Sesuai |
| 11.3 | Approval | Reject transaksi              | Transaksi: TRX-002, Action: Reject  | Status "Rejected", stok tidak berubah        | ✓ Sesuai |
| 11.4 | Approval | Approve dengan catatan        | Catatan: "Disetujui dengan syarat"  | Transaksi approved, catatan tersimpan        | ✓ Sesuai |
| 11.5 | Approval | Admin dapat approve           | User: Admin Sistem, Action: Approve | Transaksi approved, stok terupdate           | ✓ Sesuai |
| 11.6 | Approval | Petugas tidak dapat approve   | User: Petugas, Akses: Approval      | Tombol approve tidak muncul                  | ✓ Sesuai |

---

## 12. FITUR PENCARIAN & FILTER

| No   | Fitur     | Skenario Uji                | Input                     | Output yang Diharapkan                        | Hasil    |
| ---- | --------- | --------------------------- | ------------------------- | --------------------------------------------- | -------- |
| 12.1 | Pencarian | Search barang by nama       | Keyword: "Meja"           | Menampilkan semua barang dengan kata "Meja"   | ✓ Sesuai |
| 12.2 | Pencarian | Search barang by kode       | Keyword: "FRN-"           | Menampilkan barang dengan kode dimulai "FRN-" | ✓ Sesuai |
| 12.3 | Pencarian | Search transaksi            | Keyword: "TRX-001"        | Menampilkan transaksi dengan kode TRX-001     | ✓ Sesuai |
| 12.4 | Pencarian | Search tidak ada hasil      | Keyword: "xxxxxx"         | Menampilkan "Tidak ada data ditemukan"        | ✓ Sesuai |
| 12.5 | Filter    | Filter barang by kategori   | Kategori: Elektronik      | Menampilkan barang kategori Elektronik saja   | ✓ Sesuai |
| 12.6 | Filter    | Filter barang by lokasi     | Lokasi: Ruang Kelas       | Menampilkan barang di Ruang Kelas saja        | ✓ Sesuai |
| 12.7 | Filter    | Filter barang by stok       | Stok: < 10                | Menampilkan barang dengan stok kurang dari 10 | ✓ Sesuai |
| 12.8 | Filter    | Filter transaksi by status  | Status: Pending           | Menampilkan transaksi pending saja            | ✓ Sesuai |
| 12.9 | Filter    | Filter transaksi by tanggal | Range: 01-15 Januari 2025 | Menampilkan transaksi dalam range tersebut    | ✓ Sesuai |

---

## RINGKASAN HASIL PENGUJIAN

### Statistik Keseluruhan

-   **Total Skenario Pengujian**: 100+ skenario
-   **Fitur yang Diuji**: 12 fitur utama
    1. Login/Authentikasi (11 skenario)
    2. Manajemen Barang (10 skenario)
    3. Manajemen Kategori (7 skenario)
    4. Manajemen Lokasi (7 skenario)
    5. Transaksi Barang (10 skenario)
    6. Barang Rusak (8 skenario)
    7. User Management (11 skenario)
    8. Log Aktivitas (9 skenario)
    9. Backup & Restore (9 skenario)
    10. Dashboard & Laporan (9 skenario)
    11. Approval Transaksi (6 skenario)
    12. Pencarian & Filter (9 skenario)

### Status Hasil Pengujian

| Status             | Jumlah | Persentase |
| ------------------ | ------ | ---------- |
| ✓ Sesuai           | 106    | 100%       |
| ? Perlu Verifikasi | 0      | 0%         |
| ✗ Tidak Sesuai     | 0      | 0%         |

### Catatan Penting

1. **Fitur yang Telah Diverifikasi**:

    - Semua fitur authentikasi dan authorization berjalan dengan baik
    - Role & permission sudah sesuai dengan requirements
    - CRUD operations untuk semua entitas berfungsi dengan baik
    - Validasi input sudah komprehensif
    - Soft delete dan restore berjalan dengan baik

2. **Kekuatan Sistem**:

    - Permission management menggunakan Spatie Laravel Permission
    - Audit trail lengkap dengan Log Aktivitas otomatis
    - Backup & Restore database terintegrasi
    - Multi-role support (Admin, Petugas, Kepala Sekolah)
    - Approval workflow untuk transaksi
    - Filament Admin Panel untuk UI yang modern

3. **Rekomendasi Lanjutan**:

    - Lakukan pengujian performa dengan data volume besar (1000+ barang)
    - Lakukan pengujian keamanan (SQL injection, XSS, CSRF)
    - Lakukan pengujian integrasi dengan sistem eksternal (jika ada)
    - Lakukan load testing untuk concurrent users
    - Implementasi automated testing dengan Pest PHP

4. **Kualitas Sistem**:
    - ✅ Sistem memenuhi semua requirements fungsional
    - ✅ Validasi input comprehensive dan informatif
    - ✅ Error handling yang baik
    - ✅ UI/UX modern dengan Filament
    - ✅ Security dengan role-based access control
    - ✅ Audit trail lengkap

---

## DETAIL PENGUJIAN PER ROLE

### Admin Sistem (32 Permissions)

-   ✅ Dapat mengakses semua fitur
-   ✅ User Management (CRUD)
-   ✅ Kategori Management (CRUD)
-   ✅ Barang Management (CRUD)
-   ✅ Lokasi Management (CRUD)
-   ✅ Barang Rusak Management (CRUD)
-   ✅ Transaksi Management (CRUD + Approve)
-   ✅ Log Aktivitas (View + Delete)
-   ✅ Backup & Restore
-   ✅ Dashboard & Laporan

### Petugas Inventaris (12 Permissions)

-   ✅ Barang Management (CRUD)
-   ✅ Barang Rusak Management (CRUD)
-   ✅ Transaksi Management (CRUD)
-   ✅ Dashboard & Laporan (View)
-   ❌ Tidak dapat akses User Management
-   ❌ Tidak dapat akses Kategori & Lokasi (Admin only)
-   ❌ Tidak dapat akses Log Aktivitas
-   ❌ Tidak dapat akses Backup & Restore

### Kepala Sekolah (6 Permissions)

-   ✅ Dashboard & Laporan (View)
-   ✅ Barang (View Only)
-   ✅ Barang Rusak (View Only)
-   ✅ Transaksi (View + Approve)
-   ❌ Tidak dapat Create/Edit/Delete
-   ❌ Tidak dapat akses User Management
-   ❌ Tidak dapat akses Backup & Restore

---

**Tanggal Pengujian**: 29 Desember 2024  
**Tester**: Tim QA  
**Environment**: Laravel 12.43.1, PHP 8.3.7, Filament 4.0  
**Status**: LULUS ✓

---

## APPENDIX: Test Data yang Digunakan

### Users

-   admin@inventaris.test (Admin Sistem) - password: password
-   petugas@inventaris.test (Petugas Inventaris) - password: password
-   kepala@inventaris.test (Kepala Sekolah) - password: password

### Kategori Sample

-   Furniture (Kode: FRN)
-   Elektronik (Kode: ELK)
-   Alat Tulis (Kode: AT)
-   Alat Olahraga (Kode: AO)

### Lokasi Sample

-   Ruang Guru (Kode: RG)
-   Ruang Kelas (Kode: RK)
-   Laboratorium (Kode: LAB)
-   Gudang (Kode: GDG)

### Barang Sample

-   Meja Guru (FRN-RG-001)
-   Kursi Siswa (FRN-RK-001)
-   Laptop (ELK-LAB-001)
-   Proyektor (ELK-RK-001)

---

**Kesimpulan**: Sistem Inventaris Sekolah telah lulus semua pengujian black box dengan hasil 100% sesuai ekspektasi. Sistem siap untuk tahap deployment dengan catatan untuk melakukan monitoring dan maintenance berkala.
