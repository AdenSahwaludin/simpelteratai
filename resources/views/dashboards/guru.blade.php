@extends('layouts.dashboard')

@section('title', 'Guru Dashboard')
@section('nav-color', 'bg-green-600')
@section('sidebar-color', 'bg-green-600')
@section('dashboard-title', 'Guru Dashboard')
@section('user-name', auth('guru')->user()->nama)
@section('user-role', 'Guru')
@section('welcome-message',
    'Selamat datang di dashboard Guru. Kelola jadwal mengajar, nilai siswa, dan komunikasi
    dengan orang tua dari sini.')

@section('sidebar-menu')
    <a href="#"
        class="sidebar-menu-item flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-green-50 rounded-lg transition font-medium">
        <i class="fas fa-chart-bar text-green-600 icon"></i>
        <span>Dashboard</span>
    </a>

    <div class="px-4 py-2">
        <p class="sidebar-category-label text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengajaran</p>
    </div>

    <a href="#"
        class="sidebar-menu-item flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-green-50 rounded-lg transition">
        <i class="fas fa-layer-group text-blue-500 icon"></i>
        <span>Kelas Saya</span>
    </a>

    <a href="#"
        class="sidebar-menu-item flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-green-50 rounded-lg transition">
        <i class="fas fa-pencil text-green-500 icon"></i>
        <span>Input Nilai</span>
    </a>

    <a href="#"
        class="sidebar-menu-item flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-green-50 rounded-lg transition">
        <i class="fas fa-users text-purple-500 icon"></i>
        <span>Data Siswa</span>
    </a>

    <a href="#"
        class="sidebar-menu-item flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-green-50 rounded-lg transition">
        <i class="fas fa-calendar text-orange-500 icon"></i>
        <span>Jadwal Mengajar</span>
    </a>

    <div class="px-4 py-2">
        <p class="sidebar-category-label text-xs font-semibold text-gray-500 uppercase tracking-wider">Komunikasi</p>
    </div>

    <a href="#"
        class="sidebar-menu-item flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-green-50 rounded-lg transition">
        <i class="fas fa-envelope text-red-500 icon"></i>
        <span>Pesan</span>
    </a>

    <div class="px-4 py-2">
        <p class="sidebar-category-label text-xs font-semibold text-gray-500 uppercase tracking-wider">Akun</p>
    </div>

    <a href="#"
        class="sidebar-menu-item flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-green-50 rounded-lg transition">
        <i class="fas fa-cog text-gray-600 icon"></i>
        <span>Pengaturan</span>
    </a>
@endsection

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center gap-4 mb-4">
            <div class="bg-green-100 p-3 rounded-lg">
                <i class="fas fa-layer-group text-green-600 text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800">Kelas Saya</h3>
                <p class="text-sm text-gray-600">Lihat daftar kelas yang Anda ajarkan</p>
            </div>
        </div>
        <button class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded transition font-medium">
            Lihat Kelas
        </button>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center gap-4 mb-4">
            <div class="bg-blue-100 p-3 rounded-lg">
                <i class="fas fa-pencil text-blue-600 text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800">Input Nilai</h3>
                <p class="text-sm text-gray-600">Input nilai dan perkembangan siswa</p>
            </div>
        </div>
        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded transition font-medium">
            Input Nilai
        </button>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center gap-4 mb-4">
            <div class="bg-purple-100 p-3 rounded-lg">
                <i class="fas fa-users text-purple-600 text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800">Data Siswa</h3>
                <p class="text-sm text-gray-600">Lihat profil siswa dan kehadiran</p>
            </div>
        </div>
        <button class="w-full bg-purple-600 hover:bg-purple-700 text-white py-2 rounded transition font-medium">
            Lihat Data
        </button>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center gap-4 mb-4">
            <div class="bg-orange-100 p-3 rounded-lg">
                <i class="fas fa-calendar text-orange-600 text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800">Jadwal Mengajar</h3>
                <p class="text-sm text-gray-600">Lihat jadwal mengajar Anda</p>
            </div>
        </div>
        <button class="w-full bg-orange-600 hover:bg-orange-700 text-white py-2 rounded transition font-medium">
            Lihat Jadwal
        </button>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center gap-4 mb-4">
            <div class="bg-red-100 p-3 rounded-lg">
                <i class="fas fa-envelope text-red-600 text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800">Pesan</h3>
                <p class="text-sm text-gray-600">Komunikasi dengan orang tua siswa</p>
            </div>
        </div>
        <button class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded transition font-medium">
            Lihat Pesan
        </button>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center gap-4 mb-4">
            <div class="bg-gray-100 p-3 rounded-lg">
                <i class="fas fa-cog text-gray-600 text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800">Pengaturan</h3>
                <p class="text-sm text-gray-600">Pengaturan profil dan preferensi</p>
            </div>
        </div>
        <button class="w-full bg-gray-600 hover:bg-gray-700 text-white py-2 rounded transition font-medium">
            Buka Pengaturan
        </button>
    </div>
@endsection
