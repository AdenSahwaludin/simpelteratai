@extends('layouts.dashboard')

@section('title', 'Orang Tua Dashboard')
@section('nav-color', 'bg-purple-600')
@section('sidebar-color', 'bg-purple-600')
@section('dashboard-title', 'Orang Tua Dashboard')
@section('user-name', auth('orangtua')->user()->nama)
@section('user-role', 'Orang Tua')
@section('welcome-message', 'Anda berhasil login sebagai Orang Tua. Pantau perkembangan anak Anda dari dashboard ini.')

@section('sidebar-menu')
    <a href="#"
        class="sidebar-menu-item flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-purple-100 rounded-lg transition font-medium">
        <i class="fas fa-chart-bar text-purple-600 icon"></i>
        <span>Dashboard</span>
    </a>

    <div class="px-4 py-2">
        <p class="sidebar-category-label text-xs font-semibold text-gray-500 uppercase tracking-wider">Anak</p>
    </div>

    <a href="#"
        class="sidebar-menu-item flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-purple-100 rounded-lg transition">
        <i class="fas fa-child text-pink-500 icon"></i>
        <span>Data Anak</span>
    </a>

    <a href="#"
        class="sidebar-menu-item flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-purple-100 rounded-lg transition">
        <i class="fas fa-chart-line text-blue-500 icon"></i>
        <span>Perkembangan</span>
    </a>

    <a href="#"
        class="sidebar-menu-item flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-purple-100 rounded-lg transition">
        <i class="fas fa-star text-yellow-500 icon"></i>
        <span>Perilaku</span>
    </a>

    <a href="#"
        class="sidebar-menu-item flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-purple-100 rounded-lg transition">
        <i class="fas fa-calendar-check text-green-500 icon"></i>
        <span>Kehadiran</span>
    </a>

    <div class="px-4 py-2">
        <p class="sidebar-category-label text-xs font-semibold text-gray-500 uppercase tracking-wider">Komunikasi</p>
    </div>

    <a href="#"
        class="sidebar-menu-item flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-purple-100 rounded-lg transition">
        <i class="fas fa-comments text-indigo-500 icon"></i>
        <span>Chat Guru</span>
    </a>

    <a href="#"
        class="sidebar-menu-item flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-purple-100 rounded-lg transition">
        <i class="fas fa-bell text-red-500 icon"></i>
        <span>Pengumuman</span>
    </a>

    <div class="px-4 py-2">
        <p class="sidebar-category-label text-xs font-semibold text-gray-500 uppercase tracking-wider">Akun</p>
    </div>

    <a href="#"
        class="sidebar-menu-item flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-purple-100 rounded-lg transition">
        <i class="fas fa-cog text-gray-600 icon"></i>
        <span>Pengaturan</span>
    </a>
@endsection

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center gap-4 mb-4">
            <div class="bg-pink-100 p-3 rounded-lg">
                <i class="fas fa-child text-pink-600 text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800">Data Anak</h3>
                <p class="text-sm text-gray-600">Lihat profil dan data anak Anda</p>
            </div>
        </div>
        <button class="w-full bg-pink-600 hover:bg-pink-700 text-white py-2 rounded transition font-medium">
            Lihat Data Anak
        </button>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center gap-4 mb-4">
            <div class="bg-blue-100 p-3 rounded-lg">
                <i class="fas fa-chart-line text-blue-600 text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800">Perkembangan</h3>
                <p class="text-sm text-gray-600">Lihat laporan perkembangan anak</p>
            </div>
        </div>
        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded transition font-medium">
            Lihat Perkembangan
        </button>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center gap-4 mb-4">
            <div class="bg-yellow-100 p-3 rounded-lg">
                <i class="fas fa-star text-yellow-600 text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800">Perilaku</h3>
                <p class="text-sm text-gray-600">Lihat laporan perilaku anak</p>
            </div>
        </div>
        <button class="w-full bg-yellow-600 hover:bg-yellow-700 text-white py-2 rounded transition font-medium">
            Lihat Perilaku
        </button>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center gap-4 mb-4">
            <div class="bg-green-100 p-3 rounded-lg">
                <i class="fas fa-calendar-check text-green-600 text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800">Kehadiran</h3>
                <p class="text-sm text-gray-600">Lihat kehadiran anak di sekolah</p>
            </div>
        </div>
        <button class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded transition font-medium">
            Lihat Kehadiran
        </button>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center gap-4 mb-4">
            <div class="bg-indigo-100 p-3 rounded-lg">
                <i class="fas fa-comments text-indigo-600 text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800">Chat Guru</h3>
                <p class="text-sm text-gray-600">Berkomunikasi dengan guru anak</p>
            </div>
        </div>
        <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded transition font-medium">
            Buka Chat
        </button>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center gap-4 mb-4">
            <div class="bg-red-100 p-3 rounded-lg">
                <i class="fas fa-bell text-red-600 text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800">Pengumuman</h3>
                <p class="text-sm text-gray-600">Lihat pengumuman dari sekolah</p>
            </div>
        </div>
        <button class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded transition font-medium">
            Lihat Pengumuman
        </button>
    </div>
@endsection
