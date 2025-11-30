@extends('layouts.dashboard')

@section('title', 'Kelola Jadwal Siswa')
@section('nav-color', 'bg-blue-600')
@section('sidebar-color', 'bg-blue-600')
@section('dashboard-title', 'Kelola Jadwal Siswa')
@section('user-name', auth('admin')->user()->nama)
@section('user-role', 'Admin')

@section('sidebar-menu')
    <x-sidebar-menu :guard="'admin'" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Kelola Jadwal Siswa</h2>
            <p class="text-gray-600 mt-2">Lihat dan kelola daftar siswa yang terdaftar pada setiap jadwal</p>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Jadwal</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $jadwals->total() }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <i class="fas fa-calendar-alt text-blue-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Siswa Terdaftar</p>
                        <p class="text-2xl font-bold text-green-600">{{ $jadwals->sum('siswa_count') }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-lg">
                        <i class="fas fa-users text-green-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Rata-rata Siswa/Jadwal</p>
                        <p class="text-2xl font-bold text-purple-600">
                            {{ $jadwals->total() > 0 ? round($jadwals->sum('siswa_count') / $jadwals->total(), 1) : 0 }}
                        </p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-lg">
                        <i class="fas fa-chart-line text-purple-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jadwal List -->
        @forelse ($jadwals as $jadwal)
            <div class="bg-white rounded-lg shadow-md mb-4 overflow-hidden hover:shadow-lg transition">
                <div class="p-6">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <!-- Left Section: Jadwal Info -->
                        <div class="flex-1">
                            <div class="flex items-start gap-4">
                                <div class="bg-blue-100 p-3 rounded-lg shrink-0">
                                    <i class="fas fa-calendar-alt text-blue-600 text-xl"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $jadwal->id_jadwal }}</h3>
                                        <span class="bg-blue-100 text-blue-700 text-xs font-medium px-2 py-1 rounded">
                                            {{ $jadwal->ruang }}
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm text-gray-600">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-book text-orange-500"></i>
                                            <span>{{ $jadwal->mataPelajaran->nama_mapel }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-chalkboard-user text-green-500"></i>
                                            <span>{{ $jadwal->guru->nama }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-clock text-purple-500"></i>
                                            <span>{{ \Carbon\Carbon::parse($jadwal->waktu)->format('H:i') }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-users text-teal-500"></i>
                                            <span class="font-semibold">{{ $jadwal->siswa_count }} Siswa</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Section: Actions -->
                        <div class="flex flex-col gap-2 lg:w-48">
                            <a href="{{ route('admin.jadwal-siswa.edit', $jadwal->id_jadwal) }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition text-center">
                                <i class="fas fa-edit mr-2"></i>Kelola Siswa
                            </a>
                            <a href="{{ route('admin.jadwal.show', $jadwal->id_jadwal) }}"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition text-center">
                                <i class="fas fa-eye mr-2"></i>Detail Jadwal
                            </a>
                        </div>
                    </div>

                    <!-- Student Count Indicator -->
                    @if ($jadwal->siswa_count === 0)
                        <div class="mt-4 bg-yellow-50 border-l-4 border-yellow-400 p-3 rounded">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                                <span class="text-sm text-yellow-700">Belum ada siswa terdaftar pada jadwal ini</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow p-12 text-center text-gray-500">
                <i class="fas fa-calendar-times text-4xl mb-4"></i>
                <p class="text-lg font-medium mb-2">Belum ada jadwal tersedia</p>
                <p class="text-sm">Silakan tambahkan jadwal terlebih dahulu di menu Kelola Jadwal</p>
                <a href="{{ route('admin.jadwal.create') }}"
                    class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-plus mr-2"></i>Tambah Jadwal
                </a>
            </div>
        @endforelse

        <!-- Pagination -->
        @if ($jadwals->hasPages())
            <div class="mt-6">
                {{ $jadwals->links() }}
            </div>
        @endif
    </div>
@endsection
