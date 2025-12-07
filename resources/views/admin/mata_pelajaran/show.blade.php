@extends('layouts.dashboard')

@section('title', 'Detail Mata Pelajaran')
@section('dashboard-title', 'Detail Mata Pelajaran')

@section('sidebar-menu')
    <x-sidebar-menu guard="admin" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
                <li><i class="fas fa-chevron-right text-xs"></i></li>
                <li><a href="{{ route('admin.mata-pelajaran.index') }}" class="hover:text-blue-600">Data Mata
                        Pelajaran</a></li>
                <li><i class="fas fa-chevron-right text-xs"></i></li>
                <li class="text-gray-900 font-medium">Detail Mata Pelajaran</li>
            </ol>
        </nav>

        <!-- Main Info Card -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">{{ $mataPelajaran->nama_mapel }}</h1>
                    <p class="text-gray-600 text-sm mt-1">ID: {{ $mataPelajaran->id_mata_pelajaran }}</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.mata-pelajaran.edit', $mataPelajaran->id_mata_pelajaran) }}"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-edit"></i>
                        <span>Edit</span>
                    </a>
                    <a href="{{ route('admin.mata-pelajaran.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali</span>
                    </a>
                </div>
            </div>

            <!-- Info Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-blue-50 rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-calendar-alt text-blue-600 text-2xl"></i>
                        <div>
                            <p class="text-sm text-gray-600">Total Jadwal</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $mataPelajaran->jadwal_count }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-green-50 rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-chart-line text-green-600 text-2xl"></i>
                        <div>
                            <p class="text-sm text-gray-600">Laporan Perkembangan</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $mataPelajaran->laporan_perkembangan_count }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-purple-50 rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-clock text-purple-600 text-2xl"></i>
                        <div>
                            <p class="text-sm text-gray-600">Terakhir Update</p>
                            <p class="text-sm font-bold text-gray-800">
                                {{ $mataPelajaran->updated_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jadwal List -->
        @if ($mataPelajaran->jadwal_count > 0)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Jadwal Terkait</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID Jadwal
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Guru Pengampu
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Waktu
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ruang
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($mataPelajaran->jadwal as $jadwal)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-gray-900">{{ $jadwal->id_jadwal }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-900">{{ $jadwal->guru->nama ?? '-' }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-900">
                                            @if ($jadwal->waktu_mulai && $jadwal->waktu_selesai)
                                                {{ $jadwal->waktu_mulai->format('H:i') }} -
                                                {{ $jadwal->waktu_selesai->format('H:i') }}
                                            @else
                                                -
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-900">{{ $jadwal->ruang }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md p-8 text-center text-gray-500">
                <i class="fas fa-calendar-times text-4xl mb-2"></i>
                <p>Belum ada jadwal untuk mata pelajaran ini.</p>
            </div>
        @endif
    </div>
@endsection
