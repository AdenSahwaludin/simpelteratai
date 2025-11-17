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

    // Data Orang Tua
    Route::resource('orangtua', \App\Http\Controllers\Admin\OrangTuaController::class);

    // Data Guru
    Route::resource('guru', \App\Http\Controllers\Admin\GuruController::class);

    // Kelola Jadwal
    Route::resource('jadwal', \App\Http\Controllers\Admin\JadwalController::class);

    // Mata Pelajaran
    Route::resource('mata-pelajaran', \App\Http\Controllers\Admin\MataPelajaranController::class);

    // Kelola Pengumuman
    Route::resource('pengumuman', \App\Http\Controllers\Admin\PengumumanController::class);
});

// Guru routes
Route::get('/guru/dashboard', function () {
    return view('dashboards.guru');
})
    ->middleware('check.guru.role')
    ->name('guru.dashboard');

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

    // Pengumuman
    Route::get('/pengumuman', [\App\Http\Controllers\OrangTua\PengumumanController::class, 'index'])->name('pengumuman.index');
    Route::get('/pengumuman/{id}', [\App\Http\Controllers\OrangTua\PengumumanController::class, 'show'])->name('pengumuman.show');

    // Komentar
    Route::resource('komentar', \App\Http\Controllers\OrangTua\KomentarController::class);
});

// Profile routes (for all authenticated users)
Route::middleware(['auth.check'])->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/edit', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [ProfileController::class, 'passwordForm'])->name('profile.password');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
});
