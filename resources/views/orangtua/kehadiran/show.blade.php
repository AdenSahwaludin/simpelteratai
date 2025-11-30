@extends('layouts.dashboard')

@section('title', 'Detail Kehadiran')
@section('dashboard-title', 'Detail Kehadiran')
@section('nav-color', 'bg-purple-600')
@section('sidebar-color', 'bg-purple-600')
@section('user-name', auth('orangtua')->user()->nama)
@section('user-role', 'Orang Tua')

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
                <li><a href="{{ route('orangtua.kehadiran.index') }}" class="hover:text-purple-600">Kehadiran</a></li>
                <li><i class="fas fa-chevron-right text-xs"></i></li>
                <li class="text-gray-900 font-medium">Detail</li>
            </ol>
        </nav>

        <!-- Detail Card -->
        <div class="bg-white rounded-lg shadow-md p-6 max-w-3xl">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6 pb-4 border-b">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Detail Kehadiran</h1>
                    <p class="text-gray-600 text-sm mt-1">Informasi lengkap kehadiran siswa</p>
                </div>
                <!-- Status Badge -->
                <div>
                    @php
                        $statusColors = [
                            'hadir' => 'bg-green-100 text-green-800',
                            'izin' => 'bg-yellow-100 text-yellow-800',
                            'sakit' => 'bg-orange-100 text-orange-800',
                            'alpha' => 'bg-red-100 text-red-800',
                        ];
                        $color = $statusColors[$absensi->status_kehadiran] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $color }}">
                        {{ ucfirst($absensi->status_kehadiran) }}
                    </span>
                </div>
            </div>

            <!-- Information Grid -->
            <div class="space-y-6">
                <!-- Date Section -->
                <div class="bg-purple-50 p-4 rounded-lg">
                    <div class="flex items-center gap-3 mb-2">
                        <i class="fas fa-calendar-alt text-purple-600 text-xl"></i>
                        <h3 class="font-semibold text-gray-800">Tanggal</h3>
                    </div>
                    <p class="text-lg font-medium text-gray-900 ml-9">
                        {{ \Carbon\Carbon::parse($absensi->tanggal)->format('d F Y') }}
                    </p>
                </div>

                <!-- Student Section -->
                <div class="bg-green-50 p-4 rounded-lg">
                    <div class="flex items-center gap-3 mb-2">
                        <i class="fas fa-user-graduate text-green-600 text-xl"></i>
                        <h3 class="font-semibold text-gray-800">Siswa</h3>
                    </div>
                    <div class="ml-9">
                        <p class="text-lg font-medium text-gray-900">{{ $absensi->siswa->nama_siswa }}</p>
                        <p class="text-sm text-gray-600">Kelas {{ $absensi->siswa->kelas }}</p>
                    </div>
                </div>

                <!-- Subject & Teacher Section -->
                <div class="bg-purple-50 p-4 rounded-lg">
                    <div class="flex items-center gap-3 mb-3">
                        <i class="fas fa-book text-purple-600 text-xl"></i>
                        <h3 class="font-semibold text-gray-800">Mata Pelajaran</h3>
                    </div>
                    <div class="ml-9 space-y-2">
                        <div>
                            <span class="text-sm text-gray-600">Pelajaran:</span>
                            <p class="text-lg font-medium text-gray-900">
                                {{ $absensi->jadwal->mataPelajaran->nama_mata_pelajaran }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600">Guru Pengajar:</span>
                            <p class="text-base font-medium text-gray-900">{{ $absensi->jadwal->guru->nama ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Schedule Section -->
                <div class="bg-orange-50 p-4 rounded-lg">
                    <div class="flex items-center gap-3 mb-3">
                        <i class="fas fa-clock text-orange-600 text-xl"></i>
                        <h3 class="font-semibold text-gray-800">Jadwal</h3>
                    </div>
                    <div class="ml-9 grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-sm text-gray-600">Waktu:</span>
                            <p class="text-base font-medium text-gray-900">{{ $absensi->jadwal->waktu }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600">Ruang:</span>
                            <p class="text-base font-medium text-gray-900">{{ $absensi->jadwal->ruang }}</p>
                        </div>
                    </div>
                </div>

                <!-- Additional Info -->
                @if ($absensi->keterangan)
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-info-circle text-gray-600 text-xl mt-1"></i>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-800 mb-2">Keterangan</h3>
                                <p class="text-gray-700">{{ $absensi->keterangan }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Back Button -->
            <div class="mt-8 pt-6 border-t">
                <a href="{{ route('orangtua.kehadiran.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition inline-flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Daftar</span>
                </a>
            </div>
        </div>
    </div>
@endsection
