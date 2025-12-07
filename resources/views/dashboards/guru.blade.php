@extends('layouts.dashboard')

@section('title', 'Guru Dashboard')
@section('nav-color', 'bg-green-600')
@section('sidebar-color', 'bg-green-600')
@section('dashboard-title', 'Dashboard Guru')
@section('user-name', auth('guru')->user()->nama)
@section('user-role', 'Guru')

@section('sidebar-menu')
    <x-sidebar-menu :guard="'guru'" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Welcome Message -->
        <div class="bg-linear-to-r from-green-500 to-green-600 rounded-lg shadow-md p-6 mb-6 text-white">
            <h2 class="text-2xl font-bold mb-2">Selamat Datang, {{ auth('guru')->user()->nama }}!</h2>
            <p class="text-green-100">Kelola jadwal mengajar, nilai siswa, dan komunikasi dengan orang tua dari dashboard
                ini.</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Kelas -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-linear-to-br from-blue-500 to-blue-600 p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Total Kelas</p>
                            <h3 class="text-3xl font-bold mt-1">{{ $totalKelas }}</h3>
                        </div>
                        <div class="bg-white/20 p-4 rounded-full">
                            <i class="fas fa-layer-group text-3xl"></i>
                        </div>
                    </div>
                </div>
                <div class="p-4">
                    <a href="{{ route('guru.kelas-saya.index') }}"
                        class="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center">
                        <span>Lihat Kelas</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>

            <!-- Total Siswa -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-linear-to-br from-purple-500 to-purple-600 p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium">Total Siswa</p>
                            <h3 class="text-3xl font-bold mt-1">{{ $totalSiswa }}</h3>
                        </div>
                        <div class="bg-white/20 p-4 rounded-full">
                            <i class="fas fa-users text-3xl"></i>
                        </div>
                    </div>
                </div>
                <div class="p-4">
                    <a href="{{ route('guru.siswa.index') }}"
                        class="text-purple-600 hover:text-purple-700 text-sm font-medium flex items-center">
                        <span>Lihat Data Siswa</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>

            <!-- Total Jadwal -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-linear-to-br from-orange-500 to-orange-600 p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-orange-100 text-sm font-medium">Jadwal Mengajar</p>
                            <h3 class="text-3xl font-bold mt-1">{{ $totalJadwal }}</h3>
                        </div>
                        <div class="bg-white/20 p-4 rounded-full">
                            <i class="fas fa-calendar text-3xl"></i>
                        </div>
                    </div>
                </div>
                <div class="p-4">
                    <a href="{{ route('guru.jadwal.index') }}"
                        class="text-orange-600 hover:text-orange-700 text-sm font-medium flex items-center">
                        <span>Lihat Jadwal</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>

            <!-- Total Laporan -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-linear-to-br from-green-500 to-green-600 p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Total Laporan</p>
                            <h3 class="text-3xl font-bold mt-1">{{ $totalLaporan }}</h3>
                        </div>
                        <div class="bg-white/20 p-4 rounded-full">
                            <i class="fas fa-file-alt text-3xl"></i>
                        </div>
                    </div>
                </div>
                <div class="p-4">
                    <a href="{{ route('guru.laporan-lengkap.index') }}"
                        class="text-green-600 hover:text-green-700 text-sm font-medium flex items-center">
                        <span>Lihat Laporan</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Attendance Today Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Kehadiran Hari Ini</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Hadir</p>
                            <p class="text-2xl font-bold text-green-600">{{ $hadirCount }}</p>
                        </div>
                        <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                    </div>
                </div>
                <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Izin</p>
                            <p class="text-2xl font-bold text-yellow-600">{{ $izinCount }}</p>
                        </div>
                        <i class="fas fa-exclamation-circle text-yellow-500 text-2xl"></i>
                    </div>
                </div>
                <div class="bg-orange-50 p-4 rounded-lg border border-orange-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Sakit</p>
                            <p class="text-2xl font-bold text-orange-600">{{ $sakitCount }}</p>
                        </div>
                        <i class="fas fa-heartbeat text-orange-500 text-2xl"></i>
                    </div>
                </div>
                <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Alpha</p>
                            <p class="text-2xl font-bold text-red-600">{{ $alphaCount }}</p>
                        </div>
                        <i class="fas fa-times-circle text-red-500 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Jadwal Terbaru -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Jadwal Mengajar Anda</h3>
                    <a href="{{ route('guru.jadwal.index') }}"
                        class="text-green-600 hover:text-green-700 text-sm font-medium">
                        Lihat Semua
                    </a>
                </div>
                @if ($jadwalTerbaru->count() > 0)
                    <div class="space-y-3">
                        @foreach ($jadwalTerbaru as $jadwal)
                            <div class="p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-semibold text-gray-800">{{ $jadwal->mataPelajaran->nama_mapel }}
                                        </h4>
                                        <p class="text-sm text-gray-600">Ruang {{ $jadwal->ruang }} •
                                            @if ($jadwal->waktu_mulai && $jadwal->waktu_selesai)
                                                {{ $jadwal->waktu_mulai->format('H:i') }} -
                                                {{ $jadwal->waktu_selesai->format('H:i') }}
                                            @else
                                                -
                                            @endif
                                        </p>
                                    </div>
                                    <div class="bg-green-100 p-2 rounded-full">
                                        <i class="fas fa-book text-green-600"></i>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">Belum ada jadwal mengajar.</p>
                @endif
            </div>

            <!-- Pengumuman Terbaru -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Pengumuman Terbaru</h3>
                    <a href="{{ route('guru.pengumuman.index') }}"
                        class="text-green-600 hover:text-green-700 text-sm font-medium">
                        Lihat Semua
                    </a>
                </div>
                @if ($pengumumanTerbaru->count() > 0)
                    <div class="space-y-3">
                        @foreach ($pengumumanTerbaru as $pengumuman)
                            <a href="{{ route('guru.pengumuman.show', $pengumuman->id_pengumuman) }}"
                                class="block p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <div class="flex items-start gap-3">
                                    <div class="bg-red-100 p-2 rounded-full shrink-0">
                                        <i class="fas fa-bullhorn text-red-600"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-semibold text-gray-800 truncate">{{ $pengumuman->judul }}</h4>
                                        <p class="text-sm text-gray-600">{{ $pengumuman->tanggal->format('d M Y') }}</p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">Belum ada pengumuman.</p>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Aksi Cepat</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('guru.input-nilai.bulk') }}"
                    class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                    <div class="flex items-center gap-4">
                        <div class="bg-green-100 p-4 rounded-full">
                            <i class="fas fa-pencil-alt text-green-600 text-2xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800">Input Nilai</h4>
                            <p class="text-sm text-gray-600">Tambah nilai siswa</p>
                        </div>
                    </div>
                </a>
                <a href="{{ route('guru.catatan-perilaku.create') }}"
                    class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                    <div class="flex items-center gap-4">
                        <div class="bg-yellow-100 p-4 rounded-full">
                            <i class="fas fa-star text-yellow-600 text-2xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800">Catatan Perilaku</h4>
                            <p class="text-sm text-gray-600">Tambah catatan</p>
                        </div>
                    </div>
                </a>
                <a href="{{ route('guru.kelola-absensi.create') }}"
                    class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                    <div class="flex items-center gap-4">
                        <div class="bg-teal-100 p-4 rounded-full">
                            <i class="fas fa-calendar-check text-teal-600 text-2xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800">Input Absensi</h4>
                            <p class="text-sm text-gray-600">Catat kehadiran</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
