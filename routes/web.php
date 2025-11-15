<?php

use App\Http\Controllers\Admin\GuruController;
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
Route::get('/admin/dashboard', function () {
    return view('dashboards.admin');
})
    ->middleware('check.admin.role')
    ->name('admin.dashboard');

// Admin - Guru CRUD routes (Admin only)
Route::middleware(['check.admin.role'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('guru', GuruController::class);
});

// Guru routes
Route::get('/guru/dashboard', function () {
    return view('dashboards.guru');
})
    ->middleware('check.guru.role')
    ->name('guru.dashboard');

// OrangTua routes
Route::get('/orangtua/dashboard', function () {
    return view('dashboards.orangtua');
})
    ->middleware('check.orangtua.role')
    ->name('orangtua.dashboard');

// Profile routes (for all authenticated users)
Route::middleware(['auth.check'])->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/edit', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [ProfileController::class, 'passwordForm'])->name('profile.password');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
});
