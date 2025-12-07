@extends('layouts.dashboard')

@section('title', 'Jadwal Mengajar')
@section('nav-color', 'bg-green-600')
@section('sidebar-color', 'bg-green-600')
@section('dashboard-title', 'Jadwal Mengajar')
@section('user-name', auth('guru')->user()->nama)
@section('user-role', 'Guru')

@section('sidebar-menu')
    <x-sidebar-menu :guard="'guru'" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Page Header -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Jadwal Mengajar</h2>
            <p class="text-gray-600 mt-2">Jadwal mengajar Anda di semua kelas</p>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form method="GET" action="{{ route('guru.jadwal.index') }}" class="space-y-4 md:space-y-0 md:flex md:gap-4">
                <!-- Search -->
                <div class="flex-1">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari Jadwal</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="Cari berdasarkan mata pelajaran atau ruang..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <!-- Ruang Filter -->
                <div class="w-full md:w-48">
                    <label for="ruang" class="block text-sm font-medium text-gray-700 mb-2">Ruang</label>
                    <select name="ruang" id="ruang"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Semua Ruang</option>
                        @foreach ($ruangList as $r)
                            <option value="{{ $r }}" {{ request('ruang') == $r ? 'selected' : '' }}>
                                {{ $r }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex gap-2 items-end">
                    <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors duration-300">
                        <i class="fas fa-search mr-2"></i>Cari
                    </button>
                    <a href="{{ route('guru.jadwal.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors duration-300">
                        <i class="fas fa-redo mr-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Jadwal List -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            @if ($jadwal->isEmpty())
                <div class="p-6 text-center text-gray-500">
                    <i class="fas fa-calendar-alt text-4xl mb-4 text-gray-400"></i>
                    <p>Tidak ada jadwal mengajar yang ditemukan.</p>
                </div>
            @else
                <!-- Desktop Table View -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID Jadwal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Hari</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Waktu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Mata Pelajaran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ruang</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($jadwal as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-gray-900">{{ $item->id_jadwal }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full font-semibold">
                                            {{ $item->hari ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-600">
                                            @if ($item->waktu_mulai && $item->waktu_selesai)
                                                {{ $item->waktu_mulai->format('H:i') }} -
                                                {{ $item->waktu_selesai->format('H:i') }}
                                            @else
                                                -
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full">
                                            {{ $item->mataPelajaran->nama_mapel }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-600">{{ $item->ruang }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden p-4 space-y-4">
                    @foreach ($jadwal as $item)
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <span class="bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full">
                                        {{ $item->mataPelajaran->nama_mapel }}
                                    </span>
                                    <p class="text-sm text-gray-600 mt-2">{{ $item->id_jadwal }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-2 text-sm">
                                <div>
                                    <p class="text-gray-500">Hari</p>
                                    <p class="font-bold text-blue-600">{{ $item->hari ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Waktu</p>
                                    <p class="font-medium text-gray-800">
                                        @if ($item->waktu_mulai && $item->waktu_selesai)
                                            {{ $item->waktu_mulai->format('H:i') }} -
                                            {{ $item->waktu_selesai->format('H:i') }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Ruang</p>
                                    <p class="font-medium text-gray-800">{{ $item->ruang }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $jadwal->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
