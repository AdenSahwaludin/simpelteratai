@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')
@section('nav-color', 'bg-blue-600')
@section('sidebar-color', 'bg-blue-600')
@section('dashboard-title', 'Admin Dashboard')
@section('user-name', auth('admin')->user()->nama)
@section('user-role', 'Admin')
@section('welcome-message',
    'Selamat datang kembali! Berikut adalah ringkasan data dan aktivitas TK Teratai untuk hari
    ini.')

@section('sidebar-menu')
    <x-sidebar-menu :guard="'admin'" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Statistics Cards Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Siswa Card -->
            <div
                class="bg-linear-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white transform hover:scale-105 transition">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total Siswa</p>
                        <h3 class="text-3xl font-bold mt-2">{{ $totalSiswa }}</h3>
                        <p class="text-blue-100 text-xs mt-2">Aktif terdaftar</p>
                    </div>
                    <div class="bg-blue-400 bg-opacity-30 p-3 rounded-lg">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                </div>
                <a href="{{ route('admin.siswa.index') }}"
                    class="mt-4 inline-block text-blue-100 hover:text-white text-sm font-medium transition">
                    Kelola Siswa <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            <!-- Total Guru Card -->
            <div
                class="bg-linear-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white transform hover:scale-105 transition">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Total Guru</p>
                        <h3 class="text-3xl font-bold mt-2">{{ $totalGuru }}</h3>
                        <p class="text-green-100 text-xs mt-2">Pengajar aktif</p>
                    </div>
                    <div class="bg-green-400 bg-opacity-30 p-3 rounded-lg">
                        <i class="fas fa-chalkboard-user text-2xl"></i>
                    </div>
                </div>
                <a href="{{ route('admin.guru.index') }}"
                    class="mt-4 inline-block text-green-100 hover:text-white text-sm font-medium transition">
                    Kelola Guru <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            <!-- Total Orang Tua Card -->
            <div
                class="bg-linear-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white transform hover:scale-105 transition">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Total Orang Tua</p>
                        <h3 class="text-3xl font-bold mt-2">{{ $totalOrangTua }}</h3>
                        <p class="text-purple-100 text-xs mt-2">Terdaftar</p>
                    </div>
                    <div class="bg-purple-400 bg-opacity-30 p-3 rounded-lg">
                        <i class="fas fa-people-roof text-2xl"></i>
                    </div>
                </div>
                <a href="{{ route('admin.orangtua.index') }}"
                    class="mt-4 inline-block text-purple-100 hover:text-white text-sm font-medium transition">
                    Kelola Orang Tua <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            <!-- Total Mata Pelajaran Card -->
            <div
                class="bg-linear-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white transform hover:scale-105 transition">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-orange-100 text-sm font-medium">Mata Pelajaran</p>
                        <h3 class="text-3xl font-bold mt-2">{{ $totalMataPelajaran }}</h3>
                        <p class="text-orange-100 text-xs mt-2">Tersedia</p>
                    </div>
                    <div class="bg-orange-400 bg-opacity-30 p-3 rounded-lg">
                        <i class="fas fa-book text-2xl"></i>
                    </div>
                </div>
                <a href="{{ route('admin.mata-pelajaran.index') }}"
                    class="mt-4 inline-block text-orange-100 hover:text-white text-sm font-medium transition">
                    Kelola Pelajaran <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Attendance Statistics -->
            <div class="lg:col-span-1 bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Absensi Hari Ini</h3>
                    <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
                </div>

                @php
                    $totalAbsensi = $hadir + $izin + $sakit + $alpha;
                @endphp

                @if ($totalAbsensi > 0)
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                                <span class="text-sm text-gray-600">Hadir</span>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-800">{{ $hadir }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ round(($hadir / $totalAbsensi) * 100, 1) }}%
                                </p>
                            </div>
                        </div>

                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full"
                                style="width: {{ round(($hadir / $totalAbsensi) * 100, 1) }}%">
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                                <span class="text-sm text-gray-600">Izin</span>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-800">{{ $izin }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ round(($izin / $totalAbsensi) * 100, 1) }}%
                                </p>
                            </div>
                        </div>

                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-yellow-500 h-2 rounded-full"
                                style="width: {{ round(($izin / $totalAbsensi) * 100, 1) }}%">
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-orange-500"></div>
                                <span class="text-sm text-gray-600">Sakit</span>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-800">{{ $sakit }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ round(($sakit / $totalAbsensi) * 100, 1) }}%
                                </p>
                            </div>
                        </div>

                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-orange-500 h-2 rounded-full"
                                style="width: {{ round(($sakit / $totalAbsensi) * 100, 1) }}%">
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                <span class="text-sm text-gray-600">Alpha</span>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-800">{{ $alpha }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ round(($alpha / $totalAbsensi) * 100, 1) }}%
                                </p>
                            </div>
                        </div>

                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-red-500 h-2 rounded-full"
                                style="width: {{ round(($alpha / $totalAbsensi) * 100, 1) }}%">
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-2"></i>
                        <p class="text-sm">Belum ada data absensi hari ini</p>
                    </div>
                @endif
            </div>

            <!-- Class Distribution -->
            <div class="lg:col-span-1 bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Distribusi Kelas</h3>
                    <i class="fas fa-chart-bar text-purple-600 text-xl"></i>
                </div>

                @if ($kelasDistribution->count() > 0)
                    <div class="space-y-3">
                        @foreach ($kelasDistribution as $kelas)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600 font-medium">{{ $kelas->id_kelas }}</span>
                                <div class="flex items-center gap-2">
                                    <div class="w-32 bg-gray-200 rounded-full h-2">
                                        <div class="bg-purple-500 h-2 rounded-full"
                                            style="width: {{ round(($kelas->count / $totalSiswa) * 100, 1) }}%">
                                        </div>
                                    </div>
                                    <span
                                        class="text-sm font-bold text-gray-800 w-8 text-right">{{ $kelas->count }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-2"></i>
                        <p class="text-sm">Belum ada data kelas</p>
                    </div>
                @endif
            </div>

            <!-- Academic Performance -->
            <div class="lg:col-span-1 bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Nilai Rata-rata</h3>
                    <i class="fas fa-chart-line text-green-600 text-xl"></i>
                </div>

                <div class="text-center py-8">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full"
                        style="background: conic-gradient(#22c55e 0deg {{ $averageScore * 3.6 }}deg, #e5e7eb {{ $averageScore * 3.6 }}deg);">
                        <div class="bg-white rounded-full w-20 h-20 flex items-center justify-center">
                            <span class="text-2xl font-bold text-gray-800">{{ round($averageScore, 1) }}</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mt-4">Nilai rata-rata siswa</p>
                    <p class="text-xs text-gray-500 mt-1">Dari semua mata pelajaran</p>
                </div>
            </div>
        </div>

        <!-- Bottom Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Top Students -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800">5 Siswa Terbaik</h3>
                    <i class="fas fa-star text-yellow-500 text-xl"></i>
                </div>

                @if ($topStudents->count() > 0)
                    <div class="space-y-3">
                        @foreach ($topStudents as $index => $siswa)
                            <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg">
                                <div class="shrink-0">
                                    <span
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-500 text-white font-bold text-sm">
                                        @if ($index == 0)
                                            🥇
                                        @elseif ($index == 1)
                                            🥈
                                        @elseif ($index == 2)
                                            🥉
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </span>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-800 text-sm">{{ $siswa->nama }}</p>
                                    <p class="text-xs text-gray-500">{{ $siswa->kelas?->id_kelas ?? 'N/A' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-gray-800">{{ round($siswa->average_score, 1) }}</p>
                                    <p class="text-xs text-gray-500">Rata-rata</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-2"></i>
                        <p class="text-sm">Belum ada data siswa</p>
                    </div>
                @endif
            </div>

            <!-- Recent Announcements -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Pengumuman Terbaru</h3>
                    <a href="{{ route('admin.pengumuman.index') }}"
                        class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                @if ($recentPengumuman->count() > 0)
                    <div class="space-y-3">
                        @foreach ($recentPengumuman as $pengumuman)
                            <div class="p-3 bg-gray-50 rounded-lg border-l-4 border-red-500">
                                <p class="font-medium text-gray-800 text-sm line-clamp-2">{{ $pengumuman->judul }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    {{ $pengumuman->tanggal->format('d M Y') }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-2"></i>
                        <p class="text-sm">Belum ada pengumuman</p>
                    </div>
                @endif

                <a href="{{ route('admin.pengumuman.create') }}"
                    class="mt-6 w-full bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg transition font-medium text-center flex items-center justify-center gap-2">
                    <i class="fas fa-plus"></i>
                    <span>Buat Pengumuman</span>
                </a>
            </div>

            <!-- Recent Students -->
            <div class="bg-white rounded-lg shadow-md p-6 lg:col-span-2">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800">5 Siswa Terbaru Terdaftar</h3>
                    <a href="{{ route('admin.siswa.index') }}"
                        class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                @if ($recentSiswa->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b">
                                <tr>
                                    <th class="px-4 py-2 text-left font-semibold text-gray-700">Nama Siswa</th>
                                    <th class="px-4 py-2 text-left font-semibold text-gray-700">Kelas</th>
                                    <th class="px-4 py-2 text-left font-semibold text-gray-700">Orang Tua</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentSiswa as $siswa)
                                    <tr class="border-b hover:bg-gray-50 transition">
                                        <td class="px-4 py-3 text-gray-800 font-medium">{{ $siswa->nama }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $siswa->kelas->id_kelas ?? ' ' }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $siswa->orangTua->nama ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-2"></i>
                        <p class="text-sm">Belum ada siswa terdaftar</p>
                    </div>
                @endif

                <a href="{{ route('admin.siswa.create') }}"
                    class="mt-6 w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-lg transition font-medium text-center flex items-center justify-center gap-2">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Siswa Baru</span>
                </a>
            </div>
        </div>
    </div>
@endsection
