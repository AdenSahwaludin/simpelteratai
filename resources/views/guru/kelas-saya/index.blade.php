@extends('layouts.dashboard')

@section('title', 'Kelas Saya')
@section('nav-color', 'bg-green-600')
@section('sidebar-color', 'bg-green-600')
@section('dashboard-title', 'Kelas Saya')
@section('user-name', auth('guru')->user()->nama)
@section('user-role', 'Guru')

@section('sidebar-menu')
    <x-sidebar-menu :guard="'guru'" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Page Header -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Kelas yang Saya Ajar</h2>
            <p class="text-gray-600 mt-2">Daftar semua kelas yang Anda ajar beserta jumlah siswa dan mata pelajaran</p>
        </div>

        @if ($kelasData->isEmpty())
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            Anda belum memiliki jadwal mengajar. Silakan hubungi admin untuk menambahkan jadwal.
                        </p>
                    </div>
                </div>
            </div>
        @else
            <!-- Classes Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($kelasData as $kelas)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                        <div class="bg-green-600 text-white p-6 rounded-t-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-2xl font-bold">{{ $kelas['ruang'] }}</h3>
                                    <p class="text-green-100 text-sm mt-1">Kelas</p>
                                </div>
                                <div class="bg-green-500 bg-opacity-50 p-3 rounded-lg">
                                    <i class="fas fa-school text-3xl"></i>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <!-- Statistics -->
                            <div class="space-y-3 mb-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600 flex items-center">
                                        <i class="fas fa-users mr-2 text-green-600"></i>
                                        Total Siswa
                                    </span>
                                    <span class="font-semibold text-gray-800">{{ $kelas['siswa_count'] }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600 flex items-center">
                                        <i class="fas fa-calendar-alt mr-2 text-green-600"></i>
                                        Jadwal Mengajar
                                    </span>
                                    <span class="font-semibold text-gray-800">{{ $kelas['jadwal_count'] }}</span>
                                </div>
                            </div>

                            <!-- Mata Pelajaran List -->
                            <div class="border-t pt-4 mb-4">
                                <p class="text-sm text-gray-600 mb-2 font-medium">Mata Pelajaran:</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($kelas['mata_pelajaran'] as $mapel)
                                        <span class="bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full">
                                            {{ $mapel }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            <!-- View Details Button -->
                            <a href="{{ route('guru.kelas-saya.show', $kelas['ruang']) }}"
                                class="block w-full bg-green-600 hover:bg-green-700 text-white text-center py-3 rounded-lg transition-colors duration-300 font-medium">
                                <i class="fas fa-eye mr-2"></i>Lihat Detail Kelas
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
