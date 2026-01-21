@extends('layouts.dashboard')

@section('title', 'Laporan Perkembangan')
@section('dashboard-title', 'Laporan Perkembangan')
@section('nav-color', 'bg-purple-600')
@section('sidebar-color', 'bg-purple-600')
@section('user-name', auth('orangtua')->user()->nama)
@section('user-role', 'Orang Tua')

@section('sidebar-menu')
    <x-sidebar-menu guard="orangtua" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Laporan Perkembangan Anak</h1>
                <p class="text-gray-600 text-sm mt-1">Pantau perkembangan akademik anak Anda</p>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <form action="{{ route('orangtua.perkembangan.index') }}" method="GET"
                class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Anak</label>
                    <select name="anak_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="">Semua Anak</option>
                        @foreach ($anakList as $child)
                            <option value="{{ $child->id_siswa }}" {{ $anakId == $child->id_siswa ? 'selected' : '' }}>
                                {{ $child->nama }} ({{ $child->kelas?->id_kelas ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari Mata Pelajaran</label>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari mata pelajaran..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="flex-1 bg-purple-500 hover:bg-purple-600 text-white px-6 py-2 rounded-lg transition flex items-center justify-center gap-2">
                        <i class="fas fa-search"></i>
                        <span>Filter</span>
                    </button>
                    <a href="{{ route('orangtua.perkembangan.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Siswa
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Mata Pelajaran
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pertemuan
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nilai
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Komentar
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($perkembangan as $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->siswa->nama }}</div>
                                    <div class="text-xs text-gray-500">{{ $item->siswa->kelas->id_kelas ?? ' ' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-900">{{ $item->mataPelajaran->nama_mapel }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($item->absensi && $item->absensi->pertemuan)
                                        <div class="text-sm text-gray-900">
                                            <span class="font-medium">Pertemuan
                                                {{ $item->absensi->pertemuan->pertemuan_ke }}</span>
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($item->absensi->pertemuan->tanggal)->format('d/m/Y') }}
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-500">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $item->nilai >= 80 ? 'bg-green-100 text-green-800' : ($item->nilai >= 70 ? 'bg-blue-100 text-blue-800' : ($item->nilai >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')) }}">
                                        {{ $item->nilai }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-600 line-clamp-2">{{ $item->komentar ?: '-' }}</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('orangtua.perkembangan.show', $item->id_laporan) }}"
                                        class="text-purple-600 hover:text-purple-900 transition">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-inbox text-4xl mb-2"></i>
                                    <p>Belum ada laporan perkembangan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden space-y-4">
            @forelse ($perkembangan as $item)
                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $item->siswa->nama }}</div>
                            <div class="text-xs text-gray-500">{{ $item->siswa->kelas->id_kelas ?? ' ' }}</div>
                            <div class="text-sm text-gray-700 mt-1">{{ $item->mataPelajaran->nama_mapel }}</div>
                            @if ($item->absensi && $item->absensi->pertemuan)
                                <div class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    Pertemuan {{ $item->absensi->pertemuan->pertemuan_ke }} -
                                    {{ \Carbon\Carbon::parse($item->absensi->pertemuan->tanggal)->format('d/m/Y') }}
                                </div>
                            @endif
                        </div>
                        <span
                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $item->nilai >= 80 ? 'bg-green-100 text-green-800' : ($item->nilai >= 70 ? 'bg-blue-100 text-blue-800' : ($item->nilai >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')) }}">
                            {{ $item->nilai }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $item->komentar ?: 'Tidak ada komentar' }}</p>
                    <a href="{{ route('orangtua.perkembangan.show', $item->id_laporan) }}"
                        class="w-full bg-purple-500 hover:bg-purple-600 text-white px-3 py-2 rounded text-center text-sm transition flex items-center justify-center gap-2">
                        <i class="fas fa-eye"></i> Lihat Detail
                    </a>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow-md p-8 text-center text-gray-500">
                    <i class="fas fa-inbox text-4xl mb-2"></i>
                    <p>Belum ada laporan perkembangan.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($perkembangan->hasPages())
            <div class="mt-6">
                {{ $perkembangan->links() }}
            </div>
        @endif
    </div>
@endsection
