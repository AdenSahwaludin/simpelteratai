@extends('layouts.dashboard')

@section('title', 'Detail Laporan')
@section('nav-color', 'bg-green-600')
@section('sidebar-color', 'bg-green-600')
@section('dashboard-title', 'Detail Laporan')
@section('user-name', auth('guru')->user()->nama)
@section('user-role', 'Guru')

@section('sidebar-menu')
    <x-sidebar-menu :guard="'guru'" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Detail Laporan Perkembangan</h2>
                <p class="text-gray-600 mt-2">Informasi lengkap laporan untuk orang tua</p>
            </div>
            <a href="{{ route('guru.laporan-lengkap.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-300">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Student Information -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="text-center mb-6">
                        <div class="bg-green-100 w-24 h-24 rounded-full mx-auto flex items-center justify-center mb-4">
                            <i class="fas fa-user text-4xl text-green-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">{{ $laporan->siswa->nama }}</h3>
                        <p class="text-gray-600 text-sm mt-1">{{ $laporan->siswa->id_siswa }}</p>
                        <span class="inline-block bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full mt-2">
                            {{ $laporan->siswa->kelas }}
                        </span>
                    </div>

                    <div class="border-t pt-4 space-y-3">
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Nama Orang Tua</p>
                            <p class="text-sm font-medium text-gray-800 mt-1">{{ $laporan->siswa->orangTua->nama ?? '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Email Orang Tua</p>
                            <p class="text-sm font-medium text-gray-800 mt-1">{{ $laporan->siswa->orangTua->email ?? '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase">No. Telpon</p>
                            <p class="text-sm font-medium text-gray-800 mt-1">
                                {{ $laporan->siswa->orangTua->no_telpon ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Report Details -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Laporan</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">ID Laporan</p>
                            <p class="text-lg font-medium text-gray-800">{{ $laporan->id_laporan }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Mata Pelajaran</p>
                            <p class="text-lg font-medium text-gray-800">{{ $laporan->mataPelajaran->nama_mapel }}</p>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t">
                        <div class="flex items-center justify-between mb-4">
                            <p class="text-sm text-gray-500">Nilai</p>
                            <span class="bg-blue-100 text-blue-800 text-3xl px-6 py-2 rounded-lg font-bold">
                                {{ $laporan->nilai }}
                            </span>
                        </div>
                    </div>

                    @if ($laporan->komentar)
                        <div class="mt-6 pt-6 border-t">
                            <p class="text-sm text-gray-500 mb-2">Komentar</p>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-gray-800">{{ $laporan->komentar }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg">
                    <div class="flex">
                        <div class="flex shrink-0">
                            <i class="fas fa-info-circle text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                <strong>Info:</strong> Laporan ini dapat dibagikan kepada orang tua siswa melalui sistem
                                komunikasi atau cetak untuk diserahkan secara langsung.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
