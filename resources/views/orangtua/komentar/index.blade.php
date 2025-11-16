@extends('layouts.dashboard')

@section('title', 'Komentar Saya')
@section('dashboard-title', 'Komentar Saya')

@section('sidebar-menu')
    <x-sidebar-menu guard="orangtua" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Komentar Saya</h1>
                <p class="text-gray-600 text-sm mt-1">Kelola komentar dan masukan Anda</p>
            </div>
            <a href="{{ route('orangtua.komentar.create') }}"
                class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg transition flex items-center gap-2">
                <i class="fas fa-plus"></i>
                <span>Tambah Komentar</span>
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Filter -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <form action="{{ route('orangtua.komentar.index') }}" method="GET" class="flex gap-4">
                <input type="text" name="search" value="{{ $search }}"
                    placeholder="Cari komentar..." class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                <button type="submit" class="bg-purple-500 hover:bg-purple-600 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-search"></i> Cari
                </button>
                <a href="{{ route('orangtua.komentar.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-redo"></i>
                </a>
            </form>
        </div>

        <!-- Comments -->
        <div class="space-y-4">
            @forelse ($komentar as $item)
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-sm text-gray-500">
                                <i class="fas fa-clock"></i> {{ $item->created_at->format('d M Y H:i') }}
                            </p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('orangtua.komentar.edit', $item->id_komentar) }}"
                                class="text-yellow-600 hover:text-yellow-800" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('orangtua.komentar.destroy', $item->id_komentar) }}" method="POST" class="inline"
                                onsubmit="return confirm('Yakin ingin menghapus?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <p class="text-gray-700">{{ $item->komentar }}</p>
                    </div>
                    <a href="{{ route('orangtua.komentar.show', $item->id_komentar) }}"
                        class="inline-flex items-center gap-2 text-purple-600 hover:text-purple-800 font-medium">
                        <span>Lihat Detail</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow-md p-12 text-center text-gray-500">
                    <i class="fas fa-inbox text-6xl mb-4 text-gray-300"></i>
                    <p class="text-lg">Belum ada komentar</p>
                    <a href="{{ route('orangtua.komentar.create') }}"
                        class="mt-4 inline-block bg-purple-500 hover:bg-purple-600 text-white px-6 py-2 rounded-lg transition">
                        Tambah Komentar Pertama
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($komentar->hasPages())
            <div class="mt-6">
                {{ $komentar->links() }}
            </div>
        @endif
    </div>
@endsection
