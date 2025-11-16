@extends('layouts.dashboard')

@section('title', 'Data Anak')
@section('dashboard-title', 'Data Anak')

@section('sidebar-menu')
    <x-sidebar-menu guard="orangtua" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Header Section -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Data Anak Saya</h1>
            <p class="text-gray-600 text-sm mt-1">Informasi lengkap tentang anak Anda</p>
        </div>

        @if ($anak->count() > 0)
            <!-- Desktop & Tablet View -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($anak as $child)
                    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="bg-pink-100 p-4 rounded-full">
                                <i class="fas fa-child text-pink-600 text-3xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">{{ $child->nama }}</h3>
                                <p class="text-sm text-gray-600">{{ $child->id_siswa }}</p>
                            </div>
                        </div>

                        <div class="space-y-3 mb-6">
                            <div class="flex items-center gap-2 text-sm">
                                <i class="fas fa-school text-blue-600 w-5"></i>
                                <span class="text-gray-600">Kelas:</span>
                                <span class="font-medium text-gray-800">{{ $child->kelas }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm">
                                <i class="fas fa-envelope text-green-600 w-5"></i>
                                <span class="text-gray-600">Email:</span>
                                <span class="font-medium text-gray-800 text-xs">{{ $child->email ?: '-' }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm">
                                <i class="fas fa-phone text-purple-600 w-5"></i>
                                <span class="text-gray-600">Telepon:</span>
                                <span class="font-medium text-gray-800">{{ $child->no_telpon ?: '-' }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm">
                                <i class="fas fa-map-marker-alt text-red-600 w-5"></i>
                                <span class="text-gray-600">Alamat:</span>
                                <span class="font-medium text-gray-800 text-xs">{{ $child->alamat }}</span>
                            </div>
                        </div>

                        <!-- Statistics -->
                        <div class="grid grid-cols-3 gap-2 mb-4">
                            <div class="bg-blue-50 rounded-lg p-3 text-center">
                                <p class="text-2xl font-bold text-blue-600">{{ $child->laporanPerkembangan->count() }}</p>
                                <p class="text-xs text-gray-600">Laporan</p>
                            </div>
                            <div class="bg-green-50 rounded-lg p-3 text-center">
                                <p class="text-2xl font-bold text-green-600">{{ $child->absensi->count() }}</p>
                                <p class="text-xs text-gray-600">Absensi</p>
                            </div>
                            <div class="bg-yellow-50 rounded-lg p-3 text-center">
                                <p class="text-2xl font-bold text-yellow-600">{{ $child->perilaku->count() }}</p>
                                <p class="text-xs text-gray-600">Perilaku</p>
                            </div>
                        </div>

                        <a href="{{ route('orangtua.anak.show', $child->id_siswa) }}"
                            class="w-full bg-pink-500 hover:bg-pink-600 text-white py-2 rounded-lg transition font-medium text-center flex items-center justify-center gap-2">
                            <i class="fas fa-eye"></i>
                            <span>Lihat Detail</span>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md p-12 text-center text-gray-500">
                <i class="fas fa-inbox text-6xl mb-4 text-gray-300"></i>
                <p class="text-lg font-medium">Belum ada data anak</p>
                <p class="text-sm mt-2">Hubungi admin untuk menambahkan data anak Anda</p>
            </div>
        @endif
    </div>
@endsection
