@extends('layouts.dashboard')

@section('title', 'Detail Pengumuman')
@section('nav-color', 'bg-blue-600')
@section('sidebar-color', 'bg-blue-600')
@section('dashboard-title', 'Detail Pengumuman')
@section('user-name', auth('admin')->user()->nama)
@section('user-role', 'Admin')

@section('sidebar-menu')
    <x-sidebar-menu :guard="'admin'" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="mb-4">
            <a href="{{ route('admin.pengumuman.index') }}"
                class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i>
                <span>Kembali ke Daftar Pengumuman</span>
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Header dengan Info Dasar -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-8 text-white">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="inline-block px-3 py-1 bg-white text-blue-600 rounded-full font-bold text-lg">
                                {{ $pengumuman->id_pengumuman }}
                            </span>
                        </div>
                        <h1 class="text-3xl font-bold">{{ $pengumuman->judul }}</h1>
                        <p class="text-blue-100 mt-2">
                            <i class="fas fa-calendar mr-2"></i>
                            {{ \Carbon\Carbon::parse($pengumuman->tanggal)->translatedFormat('d F Y') }}
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.pengumuman.edit', $pengumuman->id_pengumuman) }}"
                            class="inline-flex items-center px-4 py-2 bg-white text-blue-600 hover:bg-gray-100 rounded-lg transition font-medium">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </a>
                        <form action="{{ route('admin.pengumuman.destroy', $pengumuman->id_pengumuman) }}" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition font-medium">
                                <i class="fas fa-trash mr-2"></i>Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Konten Detail -->
            <div class="p-6 md:p-8">
                <!-- Isi Pengumuman -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-file-alt text-blue-600 mr-3"></i>
                        Isi Pengumuman
                    </h2>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 prose prose-sm max-w-none">
                        <div class="whitespace-pre-wrap text-gray-800 leading-relaxed">
                            {{ $pengumuman->isi }}
                        </div>
                    </div>
                </div>

                <!-- Informasi Tambahan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="border border-gray-200 rounded-lg p-4">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Tanggal
                            Pengumuman</label>
                        <p class="text-lg text-gray-800 font-medium mt-2">
                            <i class="fas fa-calendar text-blue-600 mr-2"></i>
                            {{ \Carbon\Carbon::parse($pengumuman->tanggal)->translatedFormat('d F Y') }}
                        </p>
                    </div>
                    <div class="border border-gray-200 rounded-lg p-4">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Dibuat oleh</label>
                        <p class="text-lg text-gray-800 font-medium mt-2">
                            <i class="fas fa-user text-blue-600 mr-2"></i>
                            {{ $pengumuman->admin->nama ?? 'Admin' }}
                        </p>
                    </div>
                </div>

                <!-- Metadata -->
                <div class="border-t border-gray-200 pt-6 text-xs text-gray-500">
                    <p>
                        <i class="fas fa-calendar-plus mr-2"></i>
                        Dibuat pada: {{ $pengumuman->created_at->translatedFormat('d F Y H:i') }}
                    </p>
                    <p>
                        <i class="fas fa-calendar-check mr-2"></i>
                        Diperbarui pada: {{ $pengumuman->updated_at->translatedFormat('d F Y H:i') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
