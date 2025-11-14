@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')
@section('nav-color', 'bg-blue-600')
@section('sidebar-color', 'bg-blue-600')
@section('dashboard-title', 'Admin Dashboard')
@section('user-name', auth('admin')->user()->nama)
@section('user-role', 'Admin')
@section('welcome-message',
    'Anda berhasil login sebagai Admin. Kelola data sekolah, guru, siswa, dan orang tua dari
    dashboard ini.')

@section('sidebar-menu')
    <a href="#"
        class="sidebar-menu-item flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-blue-50 rounded-lg transition font-medium">
        <i class="fas fa-chart-bar text-blue-600 icon"></i>
        <span>Dashboard</span>
    </a>

    <div class="px-4 py-2">
        <p class="sidebar-category-label text-xs font-semibold text-gray-500 uppercase tracking-wider">Data Management</p>
    </div>

    <a href="#"
        class="sidebar-menu-item flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-blue-50 rounded-lg transition">
        <i class="fas fa-users text-blue-500 icon"></i>
        <span>Data Siswa</span>
    </a>

    <a href="#"
        class="sidebar-menu-item flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-blue-50 rounded-lg transition">
        <i class="fas fa-chalkboard-user text-green-500 icon"></i>
        <span>Data Guru</span>
    </a>

    <a href="#"
        class="sidebar-menu-item flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-blue-50 rounded-lg transition">
        <i class="fas fa-people-roof text-purple-500 icon"></i>
        <span>Data Orang Tua</span>
    </a>

    <a href="#"
        class="sidebar-menu-item flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-blue-50 rounded-lg transition">
        <i class="fas fa-book text-orange-500 icon"></i>
        <span>Mata Pelajaran</span>
    </a>

    <div class="px-4 py-2">
        <p class="sidebar-category-label text-xs font-semibold text-gray-500 uppercase tracking-wider">Konten</p>
    </div>

    <a href="#"
        class="sidebar-menu-item flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-blue-50 rounded-lg transition">
        <i class="fas fa-bell text-red-500 icon"></i>
        <span>Pengumuman</span>
    </a>

    <div class="px-4 py-2">
        <p class="sidebar-category-label text-xs font-semibold text-gray-500 uppercase tracking-wider">Sistem</p>
    </div>

    <a href="#"
        class="sidebar-menu-item flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-blue-50 rounded-lg transition">
        <i class="fas fa-cog text-gray-600 icon"></i>
        <span>Pengaturan</span>
    </a>
@endsection

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center gap-4 mb-4">
            <div class="bg-blue-100 p-3 rounded-lg">
                <i class="fas fa-users text-blue-600 text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800">Data Siswa</h3>
                <p class="text-sm text-gray-600">Kelola data siswa TK Teratai</p>
            </div>
        </div>
        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded transition font-medium">
            Kelola Siswa
        </button>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center gap-4 mb-4">
            <div class="bg-green-100 p-3 rounded-lg">
                <i class="fas fa-chalkboard-user text-green-600 text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800">Data Guru</h3>
                <p class="text-sm text-gray-600">Kelola data guru dan jadwal mengajar</p>
            </div>
        </div>
        <button class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded transition font-medium">
            Kelola Guru
        </button>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center gap-4 mb-4">
            <div class="bg-purple-100 p-3 rounded-lg">
                <i class="fas fa-people-roof text-purple-600 text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800">Data Orang Tua</h3>
                <p class="text-sm text-gray-600">Kelola data orang tua siswa</p>
            </div>
        </div>
        <button class="w-full bg-purple-600 hover:bg-purple-700 text-white py-2 rounded transition font-medium">
            Kelola Orang Tua
        </button>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center gap-4 mb-4">
            <div class="bg-orange-100 p-3 rounded-lg">
                <i class="fas fa-book text-orange-600 text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800">Mata Pelajaran</h3>
                <p class="text-sm text-gray-600">Kelola mata pelajaran</p>
            </div>
        </div>
        <button class="w-full bg-orange-600 hover:bg-orange-700 text-white py-2 rounded transition font-medium">
            Kelola Pelajaran
        </button>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center gap-4 mb-4">
            <div class="bg-red-100 p-3 rounded-lg">
                <i class="fas fa-bell text-red-600 text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800">Pengumuman</h3>
                <p class="text-sm text-gray-600">Buat dan kelola pengumuman sekolah</p>
            </div>
        </div>
        <button class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded transition font-medium">
            Kelola Pengumuman
        </button>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center gap-4 mb-4">
            <div class="bg-gray-100 p-3 rounded-lg">
                <i class="fas fa-cog text-gray-600 text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800">Pengaturan</h3>
                <p class="text-sm text-gray-600">Pengaturan sistem dan profil</p>
            </div>
        </div>
        <button class="w-full bg-gray-600 hover:bg-gray-700 text-white py-2 rounded transition font-medium">
            Buka Pengaturan
        </button>
    </div>
@endsection
