@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')
@section('nav-color', 'bg-blue-600')
@section('dashboard-title', 'Admin Dashboard')
@section('user-name', auth('admin')->user()->nama)
@section('welcome-message', 'Anda berhasil login sebagai Admin. Kelola data sekolah, guru, siswa, dan orang tua dari
    dashboard ini.')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-2">📊 Data Siswa</h3>
        <p class="text-gray-600">Kelola data siswa TK Teratai</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-2">👨‍🏫 Data Guru</h3>
        <p class="text-gray-600">Kelola data guru dan jadwal mengajar</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-2">👨‍👩‍👧‍👦 Data Orang Tua</h3>
        <p class="text-gray-600">Kelola data orang tua siswa</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-2">📢 Pengumuman</h3>
        <p class="text-gray-600">Buat dan kelola pengumuman sekolah</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-2">📖 Mata Pelajaran</h3>
        <p class="text-gray-600">Kelola mata pelajaran</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-2">⚙️ Pengaturan</h3>
        <p class="text-gray-600">Pengaturan sistem dan profil</p>
    </div>
@endsection
