@extends('layouts.dashboard')

@section('title', 'Detail Pengumuman')
@section('dashboard-title', 'Detail Pengumuman')

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
                <li><a href="{{ route('orangtua.pengumuman.index') }}" class="hover:text-purple-600">Pengumuman</a></li>
                <li><i class="fas fa-chevron-right text-xs"></i></li>
                <li class="text-gray-900 font-medium">Detail</li>
            </ol>
        </nav>

        <!-- Detail Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Header with Icon -->
            <div class="bg-linear-to-r from-red-500 to-red-600 p-6 text-white">
                <div class="flex items-center gap-4">
                    <div class="bg-white/20 p-4 rounded-full">
                        <i class="fas fa-bullhorn text-3xl"></i>
                    </div>
                    <div class="flex-1">
                        <h1 class="text-2xl font-bold">{{ $pengumuman->judul }}</h1>
                        <div class="flex items-center gap-2 mt-2 text-red-100">
                            <i class="fas fa-calendar-alt"></i>
                            <span>{{ $pengumuman->tanggal->format('d F Y') }}</span>
                        </div>
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

                <!-- Meta Information -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="text-gray-600 mb-1">ID Pengumuman</div>
                            <div class="font-semibold text-gray-800">{{ $pengumuman->id_pengumuman }}</div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="text-gray-600 mb-1">Tanggal Dibuat</div>
                            <div class="font-semibold text-gray-800">{{ $pengumuman->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="text-gray-600 mb-1">Admin</div>
                            <div class="font-semibold text-gray-800">
                                @if($pengumuman->admin)
                                    {{ $pengumuman->admin->nama_admin }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Back Button -->
                <div class="mt-6">
                    <a href="{{ route('orangtua.pengumuman.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition inline-flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali ke Daftar</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
