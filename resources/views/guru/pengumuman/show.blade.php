@extends('layouts.dashboard')

@section('title', 'Detail Pengumuman')
@section('nav-color', 'bg-green-600')
@section('sidebar-color', 'bg-green-600')
@section('dashboard-title', 'Detail Pengumuman')
@section('user-name', auth('guru')->user()->nama)
@section('user-role', 'Guru')

@section('sidebar-menu')
    <x-sidebar-menu :guard="'guru'" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Detail Pengumuman</h2>
                <p class="text-gray-600 mt-2">Informasi lengkap pengumuman</p>
            </div>
            <a href="{{ route('guru.pengumuman.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-300">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Header -->
            <div class="bg-linear-to-r from-green-500 to-green-600 p-6 text-white">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h1 class="text-2xl font-bold mb-3">{{ $pengumuman->judul }}</h1>
                        <div class="flex flex-wrap items-center gap-4 text-sm">
                            <span class="flex items-center bg-white/20 px-3 py-1 rounded-full">
                                <i class="fas fa-calendar mr-2"></i>
                                {{ \Carbon\Carbon::parse($pengumuman->tanggal)->format('d M Y') }}
                            </span>
                            <span class="flex items-center bg-white/20 px-3 py-1 rounded-full">
                                <i class="fas fa-user mr-2"></i>
                                {{ $pengumuman->admin->nama ?? 'Admin' }}
                            </span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <i class="fas fa-bullhorn text-4xl text-white/30"></i>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                <div class="prose max-w-none">
                    <div class="text-gray-800 leading-relaxed whitespace-pre-wrap">
                        {{ $pengumuman->isi }}
                    </div>
                </div>

                <!-- Footer Info -->
                <div class="mt-8 pt-6 border-t">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500 mb-1">ID Pengumuman</p>
                            <p class="font-medium text-gray-800">{{ $pengumuman->id_pengumuman }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 mb-1">Tanggal Dibuat</p>
                            <p class="font-medium text-gray-800">
                                {{ \Carbon\Carbon::parse($pengumuman->tanggal)->format('d F Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 pt-6 border-t flex gap-3">
                    <a href="{{ route('guru.pengumuman.index') }}"
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition-colors duration-300 inline-flex items-center">
                        <i class="fas fa-list mr-2"></i>
                        Lihat Semua Pengumuman
                    </a>
                </div>
            </div>
        </div>

        <!-- Additional Info Card -->
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg mt-6">
            <div class="flex">
                <div class="flex shrink-0">
                    <i class="fas fa-info-circle text-blue-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        <strong>Info:</strong> Pengumuman ini dibuat oleh admin sekolah. Jika ada pertanyaan, silakan
                        hubungi admin atau bagian tata usaha.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
