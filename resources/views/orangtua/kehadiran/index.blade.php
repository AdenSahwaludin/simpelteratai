@extends('layouts.dashboard')

@section('title', 'Kehadiran')
@section('dashboard-title', 'Kehadiran')
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
            <h1 class="text-2xl font-bold text-gray-800">Data Kehadiran Anak</h1>
            <p class="text-gray-600 text-sm mt-1">Pantau kehadiran anak di sekolah</p>
        </div>

        <!-- Filter -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <form action="{{ route('orangtua.kehadiran.index') }}" method="GET"
                class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Anak</label>
                    <select name="anak_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        <option value="">Semua Anak</option>
                        @foreach ($anakList as $child)
                            <option value="{{ $child->id_siswa }}" {{ $anakId == $child->id_siswa ? 'selected' : '' }}>
                                {{ $child->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        <option value="">Semua Status</option>
                        <option value="hadir" {{ $status == 'hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="izin" {{ $status == 'izin' ? 'selected' : '' }}>Izin</option>
                        <option value="sakit" {{ $status == 'sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="alpha" {{ $status == 'alpha' ? 'selected' : '' }}>Alpha</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="flex-1 bg-purple-500 hover:bg-purple-600 text-white px-6 py-2 rounded-lg transition">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="{{ route('orangtua.kehadiran.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="hidden md:block bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Siswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mata Pelajaran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($kehadiran as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $item->tanggal->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $item->siswa->nama }}</div>
                                <div class="text-xs text-gray-500">{{ $item->siswa->kelas }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $item->jadwal->mataPelajaran->nama_mapel ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $item->status_kehadiran == 'hadir'
                                        ? 'bg-green-100 text-green-800'
                                        : ($item->status_kehadiran == 'izin'
                                            ? 'bg-yellow-100 text-yellow-800'
                                            : ($item->status_kehadiran == 'sakit'
                                                ? 'bg-orange-100 text-orange-800'
                                                : 'bg-red-100 text-red-800')) }}">
                                    {{ ucfirst($item->status_kehadiran) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('orangtua.kehadiran.show', $item->id_absensi) }}"
                                    class="text-purple-600 hover:text-purple-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-2"></i>
                                <p>Belum ada data kehadiran.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden space-y-4">
            @forelse ($kehadiran as $item)
                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $item->siswa->nama }}</div>
                            <div class="text-xs text-gray-500">{{ $item->tanggal->format('d M Y') }}</div>
                        </div>
                        <span
                            class="px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $item->status_kehadiran == 'hadir'
                                ? 'bg-green-100 text-green-800'
                                : ($item->status_kehadiran == 'izin'
                                    ? 'bg-yellow-100 text-yellow-800'
                                    : ($item->status_kehadiran == 'sakit'
                                        ? 'bg-orange-100 text-orange-800'
                                        : 'bg-red-100 text-red-800')) }}">
                            {{ ucfirst($item->status_kehadiran) }}
                        </span>
                    </div>
                    <a href="{{ route('orangtua.kehadiran.show', $item->id_absensi) }}"
                        class="w-full bg-purple-500 hover:bg-purple-600 text-white px-3 py-2 rounded text-center text-sm transition flex items-center justify-center gap-2">
                        <i class="fas fa-eye"></i> Lihat Detail
                    </a>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow-md p-8 text-center text-gray-500">
                    <i class="fas fa-inbox text-4xl mb-2"></i>
                    <p>Belum ada data kehadiran.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($kehadiran->hasPages())
            <div class="mt-6">
                {{ $kehadiran->links() }}
            </div>
        @endif
    </div>
@endsection
