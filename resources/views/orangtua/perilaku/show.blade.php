@extends('layouts.dashboard')

@section('title', 'Detail Perilaku')
@section('dashboard-title', 'Detail Perilaku')
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
                <li><a href="{{ route('orangtua.perilaku.index') }}" class="hover:text-purple-600">Perilaku</a></li>
                <li><i class="fas fa-chevron-right text-xs"></i></li>
                <li class="text-gray-900 font-medium">Detail</li>
            </ol>
        </nav>

        <!-- Detail Card -->
        <div class="bg-white rounded-lg shadow-md p-6 max-w-3xl">
            <!-- Header -->
            <div class="flex items-start justify-between mb-6 pb-4 border-b">
                <div class="flex items-center gap-4">
                    <div class="bg-yellow-100 p-4 rounded-full">
                        <i class="fas fa-star text-yellow-600 text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Catatan Perilaku</h1>
                        <div class="flex items-center gap-2 text-sm text-gray-600 mt-1">
                            <i class="fas fa-clock"></i>
                            <span>{{ $perilaku->created_at->format('d F Y, H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student Information -->
            <div class="bg-purple-50 p-4 rounded-lg mb-6">
                <h3 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-user-graduate text-purple-600"></i>
                    Informasi Siswa
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm ml-6">
                    <div>
                        <span class="text-gray-600">Nama:</span>
                        <span class="font-medium text-gray-800 ml-2">{{ $perilaku->siswa->nama }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Kelas:</span>
                        <span class="font-medium text-gray-800 ml-2">{{ $perilaku->siswa->kelas->id_kelas ?? ' ' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">ID Siswa:</span>
                        <span class="font-medium text-gray-800 ml-2">{{ $perilaku->siswa->id_siswa }}</span>
                    </div>
                </div>
            </div>

            <!-- Behavior Note -->
            <div class="mb-6">
                <h3 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-clipboard-list text-gray-600"></i>
                    Catatan Perilaku
                </h3>
                <div class="bg-gray-50 p-5 rounded-lg border-l-4 border-yellow-500">
                    <p class="text-gray-800 leading-relaxed whitespace-pre-wrap">{{ $perilaku->catatan_perilaku }}</p>
                </div>
            </div>

            <!-- Reporter Information -->
            @if ($perilaku->guru)
                <div class="bg-green-50 p-4 rounded-lg mb-6">
                    <h3 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                        <i class="fas fa-chalkboard-teacher text-green-600"></i>
                        Dilaporkan Oleh
                    </h3>
                    <div class="ml-6 text-sm">
                        <span class="text-gray-600">Guru:</span>
                        <span class="font-medium text-gray-800 ml-2">{{ $perilaku->guru->nama ?? '-' }}</span>
                    </div>
                </div>
            @endif

            <!-- Meta Information -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <h3 class="font-semibold text-gray-800 mb-3">Informasi Tambahan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                    <div>
                        <span class="text-gray-600">ID Perilaku:</span>
                        <span class="font-medium text-gray-800 ml-2">{{ $perilaku->id_perilaku }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Tanggal Dicatat:</span>
                        <span class="font-medium text-gray-800 ml-2">{{ $perilaku->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @if ($perilaku->updated_at != $perilaku->created_at)
                        <div class="md:col-span-2">
                            <span class="text-gray-600">Terakhir Diubah:</span>
                            <span
                                class="font-medium text-gray-800 ml-2">{{ $perilaku->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Back Button -->
            <div class="pt-4 border-t">
                <a href="{{ route('orangtua.perilaku.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition inline-flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Daftar</span>
                </a>
            </div>
        </div>
    </div>
@endsection
