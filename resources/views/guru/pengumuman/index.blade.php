@extends('layouts.dashboard')

@section('title', 'Pengumuman')
@section('nav-color', 'bg-green-600')
@section('sidebar-color', 'bg-green-600')
@section('dashboard-title', 'Pengumuman')
@section('user-name', auth('guru')->user()->nama)
@section('user-role', 'Guru')

@section('sidebar-menu')
    <x-sidebar-menu :guard="'guru'" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Pengumuman Sekolah</h2>
            <p class="text-gray-600 mt-2">Lihat pengumuman dan informasi penting dari sekolah</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form method="GET" action="{{ route('guru.pengumuman.index') }}" class="flex gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pengumuman..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors duration-300">
                    <i class="fas fa-search mr-2"></i>Cari
                </button>
                @if (request('search'))
                    <a href="{{ route('guru.pengumuman.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors duration-300">
                        <i class="fas fa-redo"></i>
                    </a>
                @endif
            </form>
        </div>

        @if ($pengumuman->isEmpty())
            <div class="bg-white rounded-lg shadow-md p-12 text-center text-gray-500">
                <i class="fas fa-bullhorn text-5xl mb-4 text-gray-400"></i>
                <p class="text-lg">Tidak ada pengumuman yang ditemukan.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($pengumuman as $item)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $item->judul }}</h3>
                                    <div class="flex items-center gap-4 text-sm text-gray-600">
                                        <span class="flex items-center">
                                            <i class="fas fa-calendar mr-2 text-green-600"></i>
                                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-user mr-2 text-green-600"></i>
                                            {{ $item->admin->nama ?? 'Admin' }}
                                        </span>
                                    </div>
                                </div>
                                <span class="bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full font-medium">
                                    Pengumuman
                                </span>
                            </div>

                            <div class="text-gray-700 mb-4 line-clamp-3">
                                {{ Str::limit($item->isi, 200) }}
                            </div>

                            <div class="flex items-center justify-between pt-4 border-t">
                                <a href="{{ route('guru.pengumuman.show', $item->id_pengumuman) }}"
                                    class="text-green-600 hover:text-green-800 font-medium inline-flex items-center">
                                    Baca Selengkapnya
                                    <i class="fas fa-arrow-right ml-2 text-sm"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $pengumuman->links() }}
            </div>
        @endif
    </div>
@endsection
