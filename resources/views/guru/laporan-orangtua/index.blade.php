@extends('layouts.dashboard')

@section('title', 'Laporan untuk Orang Tua')
@section('nav-color', 'bg-green-600')
@section('sidebar-color', 'bg-green-600')
@section('dashboard-title', 'Laporan untuk Orang Tua')
@section('user-name', auth('guru')->user()->nama)
@section('user-role', 'Guru')

@section('sidebar-menu')
    <x-sidebar-menu :guard="'guru'" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Laporan Perkembangan untuk Orang Tua</h2>
            <p class="text-gray-600 mt-2">Pilih laporan perkembangan siswa untuk dikirim ke orang tua</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form method="GET" action="{{ route('guru.laporan-lengkap.index') }}"
                class="space-y-4 md:space-y-0 md:flex md:gap-4">
                <div class="flex-1">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari Siswa</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="Cari berdasarkan nama siswa..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
                <div class="w-full md:w-48">
                    <label for="kelas" class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                    <select name="kelas" id="kelas"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Semua Kelas</option>
                        @foreach ($kelasList as $kls)
                            <option value="{{ $kls }}" {{ request('kelas') == $kls ? 'selected' : '' }}>
                                {{ $kls }}</option>
                        @endforeach
                    </select>
                </div>
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
                <div class="flex gap-2 items-end">
                    <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors duration-300">
                        <i class="fas fa-search mr-2"></i>Cari
                    </button>
                    <a href="{{ route('guru.laporan-lengkap.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors duration-300">
                        <i class="fas fa-redo mr-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            @if ($laporan->isEmpty())
                <div class="p-6 text-center text-gray-500">
                    <i class="fas fa-file-alt text-4xl mb-4 text-gray-400"></i>
                    <p>Tidak ada laporan perkembangan yang ditemukan.</p>
                </div>
            @else
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
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $item->id_laporan }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->siswa->nama }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full">
                                            {{ $item->siswa->kelas }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $item->mataPelajaran->nama_mapel }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full font-medium">
                                            {{ $item->nilai }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('guru.laporan-lengkap.show', $item->id_laporan) }}"
                                            class="text-green-600 hover:text-green-900">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

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
                            <a href="{{ route('guru.laporan-lengkap.show', $item->id_laporan) }}"
                                class="inline-block bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm transition-colors duration-300 w-full text-center">
                                <i class="fas fa-eye mr-1"></i>Lihat Detail
                            </a>
                        </div>
                    @endforeach
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $laporan->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
