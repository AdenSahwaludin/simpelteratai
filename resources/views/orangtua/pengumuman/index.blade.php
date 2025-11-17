@extends('layouts.dashboard')

@section('title', 'Pengumuman')
@section('dashboard-title', 'Pengumuman')
@section('nav-color', 'bg-purple-600')
@section('sidebar-color', 'bg-purple-600')
@section('user-name', auth('orangtua')->user()->nama)
@section('user-role', 'Orang Tua')

@section('sidebar-menu')
    <x-sidebar-menu guard="orangtua" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Pengumuman Sekolah</h1>
            <p class="text-gray-600 text-sm mt-1">Informasi terbaru dari sekolah</p>
        </div>

        <!-- Filter -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <form action="{{ route('orangtua.pengumuman.index') }}" method="GET" class="flex gap-4">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari pengumuman..."
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                <button type="submit" class="bg-purple-500 hover:bg-purple-600 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-search"></i> Cari
                </button>
                <a href="{{ route('orangtua.pengumuman.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-redo"></i>
                </a>
            </form>
        </div>

        <!-- Announcements -->
        <div class="space-y-4">
            @forelse ($pengumuman as $item)
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition border-l-4 border-red-500">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">{{ $item->judul }}</h3>
                            <p class="text-sm text-gray-500 mt-1">
                                <i class="fas fa-calendar-alt"></i> {{ $item->tanggal->format('d M Y') }}
                            </p>
                        </div>
                        <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-medium">
                            Pengumuman
                        </span>
                    </div>
                    <p class="text-gray-700 mb-4 line-clamp-3">{{ $item->isi }}</p>
                    <a href="{{ route('orangtua.pengumuman.show', $item->id_pengumuman) }}"
                        class="inline-flex items-center gap-2 text-purple-600 hover:text-purple-800 font-medium">
                        <span>Baca Selengkapnya</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow-md p-12 text-center text-gray-500">
                    <i class="fas fa-inbox text-6xl mb-4 text-gray-300"></i>
                    <p class="text-lg">Belum ada pengumuman</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($pengumuman->hasPages())
            <div class="mt-6">
                {{ $pengumuman->links() }}
            </div>
        @endif
    </div>
@endsection
