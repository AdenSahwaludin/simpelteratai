@extends('layouts.dashboard')

@section('title', 'Input Nilai')
@section('nav-color', 'bg-green-600')
@section('sidebar-color', 'bg-green-600')
@section('dashboard-title', 'Input Nilai')
@section('user-name', auth('guru')->user()->nama)
@section('user-role', 'Guru')

@section('sidebar-menu')
    <x-sidebar-menu :guard="'guru'" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Page Header -->
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Input Nilai Siswa</h2>
                <p class="text-gray-600 mt-2">Kelola nilai siswa untuk mata pelajaran yang Anda ajar</p>
            </div>
            <a href="{{ route('guru.input-nilai.create') }}"
                class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition-colors duration-300">
                <i class="fas fa-plus-circle mr-2"></i>Tambah Nilai
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg mb-6">
                <div class="flex">
                    <div class="flex shrink-0">
                        <i class="fas fa-check-circle text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form method="GET" action="{{ route('guru.input-nilai.index') }}"
                class="space-y-4 md:space-y-0 md:flex md:gap-4">
                <!-- Search -->
                <div class="flex-1">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari Siswa</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="Cari berdasarkan nama siswa..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <!-- Kelas Filter -->
                <div class="w-full md:w-48">
                    <label for="kelas" class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                    <select name="kelas" id="kelas"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Semua Kelas</option>
                        @foreach ($kelasList as $kls)
                            <option value="{{ $kls }}" {{ request('kelas') == $kls ? 'selected' : '' }}>
                                {{ $kls }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Mata Pelajaran Filter -->
                <div class="w-full md:w-56">
                    <label for="mata_pelajaran" class="block text-sm font-medium text-gray-700 mb-2">Mata Pelajaran</label>
                    <select name="mata_pelajaran" id="mata_pelajaran"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Semua Mapel</option>
                        @foreach ($mataPelajaranList as $mapel)
                            <option value="{{ $mapel->id_mata_pelajaran }}"
                                {{ request('mata_pelajaran') == $mapel->id_mata_pelajaran ? 'selected' : '' }}>
                                {{ $mapel->nama_mapel }}
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
                    <a href="{{ route('guru.input-nilai.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors duration-300">
                        <i class="fas fa-redo mr-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Nilai List -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            @if ($laporan->isEmpty())
                <div class="p-6 text-center text-gray-500">
                    <i class="fas fa-clipboard-list text-4xl mb-4 text-gray-400"></i>
                    <p>Tidak ada data nilai yang ditemukan.</p>
                </div>
            @else
                <!-- Desktop Table View -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID Laporan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Siswa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kelas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Mata Pelajaran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nilai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($laporan as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-gray-900">{{ $item->id_laporan }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-900">{{ $item->siswa->nama }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full">
                                            {{ $item->siswa->kelas }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-600">{{ $item->mataPelajaran->nama_mapel }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full font-medium">
                                            {{ $item->nilai }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="flex gap-2">
                                            <a href="{{ route('guru.input-nilai.edit', $item->id_laporan) }}"
                                                class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('guru.input-nilai.destroy', $item->id_laporan) }}"
                                                method="POST" class="inline"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus nilai ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden p-4 space-y-4">
                    @foreach ($laporan as $item)
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $item->siswa->nama }}</p>
                                    <p class="text-sm text-gray-600 mt-1">{{ $item->id_laporan }}</p>
                                </div>
                                <span class="bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full font-medium">
                                    {{ $item->nilai }}
                                </span>
                            </div>
                            <div class="space-y-1 text-sm mb-3">
                                <p class="text-gray-600">
                                    <span class="font-medium">Kelas:</span>
                                    <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full ml-2">
                                        {{ $item->siswa->kelas }}
                                    </span>
                                </p>
                                <p class="text-gray-600"><span class="font-medium">Mapel:</span>
                                    {{ $item->mataPelajaran->nama_mapel }}</p>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('guru.input-nilai.edit', $item->id_laporan) }}"
                                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition-colors duration-300 text-center">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                                <form action="{{ route('guru.input-nilai.destroy', $item->id_laporan) }}" method="POST"
                                    class="flex-1"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus nilai ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm transition-colors duration-300">
                                        <i class="fas fa-trash mr-1"></i>Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $laporan->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
