<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\ForgotPasswordController;
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
