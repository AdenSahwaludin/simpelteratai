@extends('layouts.dashboard')

@section('title', 'Detail Anak')
@section('dashboard-title', 'Detail Anak')

@section('sidebar-menu')
    <x-sidebar-menu guard="orangtua" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('orangtua.dashboard') }}" class="hover:text-purple-600">Dashboard</a></li>
                <li><i class="fas fa-chevron-right text-xs"></i></li>
                <li><a href="{{ route('orangtua.anak.index') }}" class="hover:text-purple-600">Data Anak</a></li>
                <li><i class="fas fa-chevron-right text-xs"></i></li>
                <li class="text-gray-900 font-medium">Detail Anak</li>
            </ol>
        </nav>

        <!-- Main Profile Card -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                <div class="flex items-center gap-4">
                    <div class="bg-pink-100 p-6 rounded-full">
                        <i class="fas fa-child text-pink-600 text-4xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">{{ $anak->nama }}</h1>
                        <p class="text-gray-600 text-sm mt-1">ID: {{ $anak->id_siswa }}</p>
                        <span class="inline-block mt-2 px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                            {{ $anak->kelas }}
                        </span>
                    </div>
                </div>
                <a href="{{ route('orangtua.anak.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </a>
            </div>

            <!-- Information Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Informasi Pribadi</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-500">Nama Lengkap</p>
                            <p class="font-medium text-gray-800">{{ $anak->nama }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Kelas</p>
                            <p class="font-medium text-gray-800">{{ $anak->kelas }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Email</p>
                            <p class="font-medium text-gray-800">{{ $anak->email ?: '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">No. Telepon</p>
                            <p class="font-medium text-gray-800">{{ $anak->no_telpon ?: '-' }}</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Informasi Lainnya</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-500">Alamat</p>
                            <p class="font-medium text-gray-800">{{ $anak->alamat }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Nama Orang Tua</p>
                            <p class="font-medium text-gray-800">{{ $anak->orangTua->nama }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Kontak Orang Tua</p>
                            <p class="font-medium text-gray-800">{{ $anak->orangTua->no_telpon }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-linear-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-blue-100 text-sm">Total Laporan Perkembangan</p>
                        <h3 class="text-3xl font-bold mt-2">{{ $anak->laporanPerkembangan->count() }}</h3>
                    </div>
                    <i class="fas fa-chart-line text-3xl text-blue-200"></i>
                </div>
            </div>

            <div class="bg-linear-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-green-100 text-sm">Total Kehadiran</p>
                        <h3 class="text-3xl font-bold mt-2">{{ $anak->absensi->count() }}</h3>
                    </div>
                    <i class="fas fa-calendar-check text-3xl text-green-200"></i>
                </div>
            </div>

            <div class="bg-linear-to-br from-yellow-500 to-yellow-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-yellow-100 text-sm">Catatan Perilaku</p>
                        <h3 class="text-3xl font-bold mt-2">{{ $anak->perilaku->count() }}</h3>
                    </div>
                    <i class="fas fa-star text-3xl text-yellow-200"></i>
                </div>
            </div>
        </div>

        <!-- Quick Access Links -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('orangtua.perkembangan.index', ['anak_id' => $anak->id_siswa]) }}"
                class="bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition text-center">
                <i class="fas fa-chart-line text-blue-500 text-2xl mb-2"></i>
                <p class="font-medium text-gray-800">Lihat Perkembangan</p>
            </a>

            <a href="{{ route('orangtua.kehadiran.index', ['anak_id' => $anak->id_siswa]) }}"
                class="bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition text-center">
                <i class="fas fa-calendar-check text-green-500 text-2xl mb-2"></i>
                <p class="font-medium text-gray-800">Lihat Kehadiran</p>
            </a>

            <a href="{{ route('orangtua.perilaku.index', ['anak_id' => $anak->id_siswa]) }}"
                class="bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition text-center">
                <i class="fas fa-star text-yellow-500 text-2xl mb-2"></i>
                <p class="font-medium text-gray-800">Lihat Perilaku</p>
            </a>

            <a href="{{ route('orangtua.anak.index') }}"
                class="bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition text-center">
                <i class="fas fa-users text-purple-500 text-2xl mb-2"></i>
                <p class="font-medium text-gray-800">Semua Anak</p>
            </a>
        </div>
    </div>
@endsection
