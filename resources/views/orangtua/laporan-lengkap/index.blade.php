@extends('layouts.dashboard')

@section('title', 'Laporan Lengkap')
@section('dashboard-title', 'Laporan Lengkap')
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
            <h1 class="text-2xl font-bold text-gray-800">Laporan Lengkap Anak</h1>
            <p class="text-gray-600 text-sm mt-1">Lihat laporan komprehensif perkembangan anak dari guru</p>
        </div>

        <!-- Filter -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <form action="{{ route('orangtua.laporan-lengkap.index') }}" method="GET"
                class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama anak..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="flex-1 bg-purple-500 hover:bg-purple-600 text-white px-6 py-2 rounded-lg transition">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    @if ($search)
                        <a href="{{ route('orangtua.laporan-lengkap.index') }}"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                            <i class="fas fa-redo"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse ($laporan as $item)
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="bg-purple-100 p-3 rounded-full shrink-0">
                            <i class="fas fa-file-alt text-purple-600 text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-800">{{ $item->siswa->nama }}</h3>
                            <p class="text-sm text-gray-600">{{ $item->siswa->kelas }}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-user"></i> {{ $item->guru->nama }}
                            </p>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 mb-4 space-y-2">
                        <div class="flex items-center text-sm">
                            <i class="fas fa-calendar text-gray-400 w-5"></i>
                            <span class="text-gray-700">
                                {{ \Carbon\Carbon::parse($item->periode_mulai)->format('d M Y') }} -
                                {{ \Carbon\Carbon::parse($item->periode_selesai)->format('d M Y') }}
                            </span>
                        </div>
                        <div class="flex items-center text-sm">
                            <i class="fas fa-paper-plane text-gray-400 w-5"></i>
                            <span class="text-gray-700">
                                {{ $item->tanggal_kirim ? \Carbon\Carbon::parse($item->tanggal_kirim)->format('d M Y H:i') : '-' }}
                            </span>
                        </div>
                    </div>

                    <a href="{{ route('orangtua.laporan-lengkap.show', $item->id_laporan_lengkap) }}"
                        class="w-full bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg transition text-center flex items-center justify-center gap-2">
                        <i class="fas fa-eye"></i> Lihat Detail
                    </a>
                </div>
            @empty
                <div class="col-span-2 bg-white rounded-lg shadow-md p-12 text-center text-gray-500">
                    <i class="fas fa-file-alt text-gray-300 text-6xl mb-4"></i>
                    <p class="text-lg">Belum ada laporan yang dikirim oleh guru.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($laporan->hasPages())
            <div class="mt-6">
                {{ $laporan->links() }}
            </div>
        @endif
    </div>
@endsection
