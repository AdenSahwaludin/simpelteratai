@extends('layouts.dashboard')

@section('title', 'Detail Perkembangan')
@section('dashboard-title', 'Detail Perkembangan')

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
                <li><a href="{{ route('orangtua.perkembangan.index') }}" class="hover:text-purple-600">Perkembangan</a></li>
                <li><i class="fas fa-chevron-right text-xs"></i></li>
                <li class="text-gray-900 font-medium">Detail</li>
            </ol>
        </nav>

        <!-- Detail Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden max-w-3xl">
            <!-- Header with Gradient -->
            <div class="bg-linear-to-r from-blue-500 to-blue-600 p-6 text-white">
                <div class="flex items-center gap-4">
                    <div class="bg-white/20 p-4 rounded-full">
                        <i class="fas fa-chart-line text-3xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">Laporan Perkembangan</h1>
                        <p class="text-blue-100 text-sm mt-1">{{ $laporan->mataPelajaran->nama_mata_pelajaran }}</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <!-- Student Information -->
                <div class="bg-blue-50 p-4 rounded-lg mb-6">
                    <h3 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                        <i class="fas fa-user-graduate text-blue-600"></i>
                        Informasi Siswa
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm ml-6">
                        <div>
                            <span class="text-gray-600">Nama:</span>
                            <span class="font-medium text-gray-800 ml-2">{{ $laporan->siswa->nama_siswa }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Kelas:</span>
                            <span class="font-medium text-gray-800 ml-2">{{ $laporan->siswa->kelas }}</span>
                        </div>
                    </div>
                </div>

                <!-- Score Section -->
                <div class="mb-6">
                    <h3 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                        <i class="fas fa-trophy text-yellow-600"></i>
                        Nilai
                    </h3>
                    <div class="flex items-center gap-4 ml-6">
                        @php
                            $nilai = $laporan->nilai;
                            if ($nilai >= 80) {
                                $badgeClass = 'bg-green-100 text-green-800 border-green-300';
                                $icon = 'fa-check-circle';
                            } elseif ($nilai >= 70) {
                                $badgeClass = 'bg-blue-100 text-blue-800 border-blue-300';
                                $icon = 'fa-thumbs-up';
                            } elseif ($nilai >= 60) {
                                $badgeClass = 'bg-yellow-100 text-yellow-800 border-yellow-300';
                                $icon = 'fa-exclamation-circle';
                            } else {
                                $badgeClass = 'bg-red-100 text-red-800 border-red-300';
                                $icon = 'fa-exclamation-triangle';
                            }
                        @endphp
                        <div class="inline-flex items-center gap-2 px-6 py-3 rounded-lg border-2 {{ $badgeClass }}">
                            <i class="fas {{ $icon }} text-xl"></i>
                            <span class="text-3xl font-bold">{{ $nilai }}</span>
                        </div>
                        <div class="text-sm text-gray-600">
                            @if ($nilai >= 80)
                                <span class="font-medium text-green-700">Sangat Baik</span>
                            @elseif ($nilai >= 70)
                                <span class="font-medium text-blue-700">Baik</span>
                            @elseif ($nilai >= 60)
                                <span class="font-medium text-yellow-700">Cukup</span>
                            @else
                                <span class="font-medium text-red-700">Perlu Perbaikan</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Subject Information -->
                <div class="bg-purple-50 p-4 rounded-lg mb-6">
                    <h3 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                        <i class="fas fa-book text-purple-600"></i>
                        Mata Pelajaran
                    </h3>
                    <div class="ml-6">
                        <p class="text-lg font-medium text-gray-900">{{ $laporan->mataPelajaran->nama_mata_pelajaran }}</p>
                        @if($laporan->mataPelajaran->deskripsi)
                            <p class="text-sm text-gray-600 mt-1">{{ $laporan->mataPelajaran->deskripsi }}</p>
                        @endif
                    </div>
                </div>

                <!-- Comments Section -->
                @if($laporan->komentar)
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                            <i class="fas fa-comment-dots text-gray-600"></i>
                            Komentar Guru
                        </h3>
                        <div class="bg-gray-50 p-4 rounded-lg border-l-4 border-blue-500 ml-6">
                            <p class="text-gray-800 leading-relaxed whitespace-pre-wrap">{{ $laporan->komentar }}</p>
                        </div>
                    </div>
                @endif

                <!-- Meta Information -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="font-semibold text-gray-800 mb-3">Informasi Laporan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                        <div>
                            <span class="text-gray-600">ID Laporan:</span>
                            <span class="font-medium text-gray-800 ml-2">{{ $laporan->id_laporan }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Tanggal Dibuat:</span>
                            <span class="font-medium text-gray-800 ml-2">{{ $laporan->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @if($laporan->updated_at != $laporan->created_at)
                            <div class="md:col-span-2">
                                <span class="text-gray-600">Terakhir Diubah:</span>
                                <span class="font-medium text-gray-800 ml-2">{{ $laporan->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Back Button -->
                <div class="pt-4 border-t">
                    <a href="{{ route('orangtua.perkembangan.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition inline-flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali ke Daftar</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
