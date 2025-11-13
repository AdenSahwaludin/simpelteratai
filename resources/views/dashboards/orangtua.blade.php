@extends('layouts.dashboard')

@section('title', 'Orang Tua Dashboard')
@section('nav-color', 'bg-purple-600')
@section('dashboard-title', 'Orang Tua Dashboard')
@section('user-name', auth('orangtua')->user()->nama)
@section('welcome-message', 'Anda berhasil login sebagai Orang Tua. Pantau perkembangan anak Anda dari dashboard ini.')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-2">👧 Data Anak</h3>
        <p class="text-gray-600">Lihat profil dan data anak Anda</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-2">📊 Perkembangan</h3>
        <p class="text-gray-600">Lihat laporan perkembangan anak</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-2">🎯 Perilaku</h3>
        <p class="text-gray-600">Lihat laporan perilaku anak</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-2">📚 Kehadiran</h3>
        <p class="text-gray-600">Lihat kehadiran anak di sekolah</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-2">💬 Chat Guru</h3>
        <p class="text-gray-600">Berkomunikasi dengan guru anak</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-2">📢 Pengumuman</h3>
        <p class="text-gray-600">Lihat pengumuman dari sekolah</p>
    </div>
@endsection
