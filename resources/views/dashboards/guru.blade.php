@extends('layouts.dashboard')

@section('title', 'Guru Dashboard')
@section('nav-color', 'bg-green-600')
@section('dashboard-title', 'Guru Dashboard')
@section('user-name', auth('guru')->user()->nama)
@section('welcome-message', 'Anda berhasil login sebagai Guru. Kelola kelas, nilai, dan perkembangan siswa dari
    dashboard ini.')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-2">📚 Kelas Saya</h3>
        <p class="text-gray-600">Lihat daftar kelas yang Anda ajarkan</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-2">📝 Input Nilai</h3>
        <p class="text-gray-600">Input nilai dan perkembangan siswa</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-2">👥 Data Siswa</h3>
        <p class="text-gray-600">Lihat profil siswa dan kehadiran</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-2">📅 Jadwal Mengajar</h3>
        <p class="text-gray-600">Lihat jadwal mengajar Anda</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-2">💬 Pesan</h3>
        <p class="text-gray-600">Komunikasi dengan orang tua siswa</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-2">⚙️ Pengaturan</h3>
        <p class="text-gray-600">Pengaturan profil dan preferensi</p>
    </div>
@endsection
