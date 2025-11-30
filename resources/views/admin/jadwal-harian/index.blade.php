@extends('layouts.dashboard')

@section('title', 'Jadwal Harian')
@section('nav-color', 'bg-blue-600')
@section('sidebar-color', 'bg-blue-600')
@section('dashboard-title', 'Jadwal Harian')
@section('user-name', auth('admin')->user()->nama)
@section('user-role', 'Admin')

@section('sidebar-menu')
    <x-sidebar-menu :guard="'admin'" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="p-6">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Kelola Jadwal Harian</h1>
                <p class="text-gray-600 mt-2">Atur jadwal kegiatan harian anak TK</p>
            </div>
            <a href="{{ route('admin.jadwal-harian.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors duration-300">
                <i class="fas fa-plus-circle mr-2"></i>Tambah Jadwal
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg mb-6">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Filter -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form method="GET" action="{{ route('admin.jadwal-harian.index') }}"
                class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" value="{{ $tanggal }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="kelas" class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                    <select name="kelas" id="kelas"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Kelas</option>
                        @foreach ($kelasList as $k)
                            <option value="{{ $k }}" {{ $kelas == $k ? 'selected' : '' }}>{{ $k }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors duration-300">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.jadwal-harian.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors duration-300">
                        <i class="fas fa-redo mr-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            @if ($jadwal->isEmpty())
                <div class="p-6 text-center text-gray-500">
                    <i class="fas fa-calendar-alt text-4xl mb-4 text-gray-400"></i>
                    <p>Tidak ada jadwal harian yang ditemukan.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tema</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kegiatan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($jadwal as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $item->tanggal->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $item->tema ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $item->waktu_mulai->format('H:i') }} -
                                        {{ $item->waktu_selesai->format('H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ Str::limit($item->kegiatan, 50) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if ($item->kelas)
                                            <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                                                {{ $item->kelas }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">Semua</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="flex gap-2">
                                            <a href="{{ route('admin.jadwal-harian.edit', $item->id_jadwal_harian) }}"
                                                class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form
                                                action="{{ route('admin.jadwal-harian.destroy', $item->id_jadwal_harian) }}"
                                                method="POST" class="inline"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $jadwal->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
