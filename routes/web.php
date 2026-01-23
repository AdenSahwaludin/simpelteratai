<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.store');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Forgot Password routes
Route::get('/forgot-password', [ForgotPasswordController::class, 'show'])->name('forgot.password');
Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->name('forgot.password.store');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.store');

// Admin routes
Route::middleware('check.admin.role')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Data Siswa
    Route::resource('siswa', \App\Http\Controllers\Admin\SiswaController::class);

    // AJAX routes untuk siswa
    Route::get('/siswa-search-orangtua', [\App\Http\Controllers\Admin\SiswaController::class, 'searchOrangTua'])->name('siswa.search-orangtua');
    Route::post('/siswa-store-orangtua', [\App\Http\Controllers\Admin\SiswaController::class, 'storeOrangTua'])->name('siswa.store-orangtua');

    // Bulk class transfer
    Route::get('/siswa-bulk-transfer', [\App\Http\Controllers\Admin\SiswaController::class, 'showBulkTransfer'])->name('siswa.bulk-transfer');
    Route::post('/siswa-bulk-transfer', [\App\Http\Controllers\Admin\SiswaController::class, 'processBulkTransfer'])->name('siswa.bulk-transfer.process');

    // Data Orang Tua
    Route::resource('orangtua', \App\Http\Controllers\Admin\OrangTuaController::class);

    // Data Guru
    Route::resource('guru', \App\Http\Controllers\Admin\GuruController::class);

    // Kelola Wali Kelas
    Route::get('/kelas', [\App\Http\Controllers\Admin\KelasController::class, 'index'])->name('kelas.index');
    Route::get('/kelas/{kelas}/edit', [\App\Http\Controllers\Admin\KelasController::class, 'edit'])->name('kelas.edit');
    Route::put('/kelas/{kelas}', [\App\Http\Controllers\Admin\KelasController::class, 'update'])->name('kelas.update');
    Route::post('/kelas', [\App\Http\Controllers\Admin\KelasController::class, 'store'])->name('kelas.store');

    // Kelola Jadwal
    Route::resource('jadwal', \App\Http\Controllers\Admin\JadwalController::class);
    Route::get('/jadwal-siswa', [\App\Http\Controllers\Admin\JadwalSiswaController::class, 'index'])->name('jadwal-siswa.index');
    Route::get('/jadwal/{id}/siswa', [\App\Http\Controllers\Admin\JadwalSiswaController::class, 'edit'])->name('jadwal-siswa.edit');
    Route::put('/jadwal/{id}/siswa', [\App\Http\Controllers\Admin\JadwalSiswaController::class, 'update'])->name('jadwal-siswa.update');

    // Mata Pelajaran
    Route::resource('mata-pelajaran', \App\Http\Controllers\Admin\MataPelajaranController::class);

    // Kelola Pengumuman
    Route::resource('pengumuman', \App\Http\Controllers\Admin\PengumumanController::class);
});

// Guru routes
Route::middleware('check.guru.role')->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Guru\DashboardController::class, 'index'])->name('dashboard');

    // Kelas Saya
    Route::get('/kelas-saya', [\App\Http\Controllers\Guru\KelasSayaController::class, 'index'])->name('kelas-saya.index');
    Route::get('/kelas-saya/{ruang}', [\App\Http\Controllers\Guru\KelasSayaController::class, 'show'])->name('kelas-saya.show');

    // Data Siswa
    Route::get('/siswa', [\App\Http\Controllers\Guru\SiswaController::class, 'index'])->name('siswa.index');
    Route::get('/siswa/{id}', [\App\Http\Controllers\Guru\SiswaController::class, 'show'])->name('siswa.show');

    // Jadwal Mengajar
    Route::get('/jadwal', [\App\Http\Controllers\Guru\JadwalController::class, 'index'])->name('jadwal.index');

    // Input Nilai
    Route::get('/input-nilai', [\App\Http\Controllers\Guru\InputNilaiController::class, 'index'])->name('input-nilai.index');
    Route::get('/input-nilai/{id}/edit', [\App\Http\Controllers\Guru\InputNilaiController::class, 'edit'])->name('input-nilai.edit');
    Route::put('/input-nilai/{id}', [\App\Http\Controllers\Guru\InputNilaiController::class, 'update'])->name('input-nilai.update');
    Route::delete('/input-nilai/{id}', [\App\Http\Controllers\Guru\InputNilaiController::class, 'destroy'])->name('input-nilai.destroy');
    Route::get('/input-nilai-bulk', [\App\Http\Controllers\Guru\InputNilaiController::class, 'bulkIndex'])->name('input-nilai.bulk');
    Route::get('/kelola-nilai-load-siswa', [\App\Http\Controllers\Guru\InputNilaiController::class, 'loadSiswaByJadwal'])->name('input-nilai.load-siswa');
    Route::get('/kelola-nilai-load-absensi', [\App\Http\Controllers\Guru\InputNilaiController::class, 'loadAbsensiByPertemuan'])->name('input-nilai.load-absensi');
    Route::post('/input-nilai-bulk', [\App\Http\Controllers\Guru\InputNilaiController::class, 'bulkStore'])->name('input-nilai.bulk-store');

    // Catatan Perilaku
    Route::resource('catatan-perilaku', \App\Http\Controllers\Guru\CatatanPerilakuController::class);

    // Kelola Absensi
    Route::resource('kelola-absensi', \App\Http\Controllers\Guru\KelolaAbsensiController::class);
    Route::get('/kelola-absensi-load-siswa', [\App\Http\Controllers\Guru\KelolaAbsensiController::class, 'loadSiswaByKelas'])->name('kelola-absensi.load-siswa');
    Route::post('/kelola-absensi-bulk-delete', [\App\Http\Controllers\Guru\KelolaAbsensiController::class, 'bulkDestroy'])->name('kelola-absensi.bulk-destroy');

    // Pengumuman
    Route::get('/pengumuman', [\App\Http\Controllers\Guru\PengumumanController::class, 'index'])->name('pengumuman.index');
    Route::get('/pengumuman/{id}', [\App\Http\Controllers\Guru\PengumumanController::class, 'show'])->name('pengumuman.show');

    // Laporan Lengkap
    Route::resource('laporan-lengkap', \App\Http\Controllers\Guru\LaporanLengkapController::class);
    Route::post('/laporan-lengkap/{id}/kirim', [\App\Http\Controllers\Guru\LaporanLengkapController::class, 'kirimKeOrangTua'])->name('laporan-lengkap.kirim');
    Route::post('/laporan-lengkap-preview', [\App\Http\Controllers\Guru\LaporanLengkapController::class, 'previewData'])->name('laporan-lengkap.preview');
    Route::post('/laporan-lengkap/{id}/komentar', [\App\Http\Controllers\Guru\LaporanLengkapController::class, 'storeKomentar'])->name('laporan-lengkap.komentar.store');
});

// OrangTua routes
Route::middleware('check.orangtua.role')->prefix('orangtua')->name('orangtua.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\OrangTua\DashboardController::class, 'index'])->name('dashboard');

    // Data Anak
    Route::get('/anak', [\App\Http\Controllers\OrangTua\AnakController::class, 'index'])->name('anak.index');
    Route::get('/anak/{id}', [\App\Http\Controllers\OrangTua\AnakController::class, 'show'])->name('anak.show');

    // Perkembangan
    Route::get('/perkembangan', [\App\Http\Controllers\OrangTua\PerkembanganController::class, 'index'])->name('perkembangan.index');
    Route::get('/perkembangan/{id}', [\App\Http\Controllers\OrangTua\PerkembanganController::class, 'show'])->name('perkembangan.show');

    // Perilaku
    Route::get('/perilaku', [\App\Http\Controllers\OrangTua\PerilakuController::class, 'index'])->name('perilaku.index');
    Route::get('/perilaku/{id}', [\App\Http\Controllers\OrangTua\PerilakuController::class, 'show'])->name('perilaku.show');

    // Kehadiran
    Route::get('/kehadiran', [\App\Http\Controllers\OrangTua\KehadiranController::class, 'index'])->name('kehadiran.index');
    Route::get('/kehadiran/{id}', [\App\Http\Controllers\OrangTua\KehadiranController::class, 'show'])->name('kehadiran.show');

    // Jadwal
    Route::get('/jadwal', [\App\Http\Controllers\OrangTua\JadwalController::class, 'index'])->name('jadwal.index');
    Route::get('/jadwal/{id}', [\App\Http\Controllers\OrangTua\JadwalController::class, 'show'])->name('jadwal.show');

    // Pengumuman
    Route::get('/pengumuman', [\App\Http\Controllers\OrangTua\PengumumanController::class, 'index'])->name('pengumuman.index');
    Route::get('/pengumuman/{id}', [\App\Http\Controllers\OrangTua\PengumumanController::class, 'show'])->name('pengumuman.show');

    // Laporan Lengkap
    Route::get('/laporan-lengkap', [\App\Http\Controllers\OrangTua\LaporanLengkapController::class, 'index'])->name('laporan-lengkap.index');
    Route::get('/laporan-lengkap/{id}', [\App\Http\Controllers\OrangTua\LaporanLengkapController::class, 'show'])->name('laporan-lengkap.show');
    Route::post('/laporan-lengkap/{id}/komentar', [\App\Http\Controllers\OrangTua\LaporanLengkapController::class, 'storeKomentar'])->name('laporan-lengkap.komentar.store');
});

// Profile routes (for all authenticated users)
Route::middleware(['auth.check'])->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/edit', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [ProfileController::class, 'passwordForm'])->name('profile.password');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
});
