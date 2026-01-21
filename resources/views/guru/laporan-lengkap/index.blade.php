@extends('layouts.dashboard')

@section('title', 'Laporan Lengkap')
@section('nav-color', 'bg-green-600')
@section('sidebar-color', 'bg-green-600')
@section('dashboard-title', 'Laporan Lengkap')
@section('user-name', auth('guru')->user()->nama)
@section('user-role', 'Guru')

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
            <h2 class="text-2xl font-bold text-gray-800">Daftar Laporan Lengkap</h2>
            <a href="{{ route('guru.laporan-lengkap.create') }}"
                class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition font-medium text-center">
                <i class="fas fa-plus mr-2"></i>Buat Laporan
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow p-4 mb-4">
            <form action="{{ route('guru.laporan-lengkap.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ $search }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="Cari nama siswa...">
                </div>
                <div class="w-full md:w-48">
                    <select name="id_kelas"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Semua Kelas</option>
                        @foreach ($kelasList as $k)
                            <option value="{{ $k->id_kelas }}" {{ $kelas == $k->id_kelas ? 'selected' : '' }}>
                                {{ $k->id_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit"
                    class="w-full md:w-auto bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition font-medium">
                    <i class="fas fa-search mr-2"></i>Cari
                </button>
                @if ($search || $kelas)
                    <a href="{{ route('guru.laporan-lengkap.index') }}"
                        class="w-full md:w-auto bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition font-medium text-center">
                        <i class="fas fa-times mr-2"></i>Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($laporan as $item)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $item->siswa->nama }}</div>
                                <div class="text-sm text-gray-500">{{ $item->siswa->kelas->id_kelas ?? ' ' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $item->periode_mulai->format('d M') }} - {{ $item->periode_selesai->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <div class="max-w-xs truncate">{{ $item->catatan_guru }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                @if ($item->dikirim_ke_ortu)
                                    <span
                                        class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                                        Terkirim
                                    </span>
                                @else
                                    <span
                                        class="inline-block px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-semibold">
                                        Draft
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-2">
                                <a href="{{ route('guru.laporan-lengkap.show', $item->id_laporan_lengkap) }}"
                                    class="text-green-600 hover:text-green-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('guru.laporan-lengkap.edit', $item->id_laporan_lengkap) }}"
                                    class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('guru.laporan-lengkap.destroy', $item->id_laporan_lengkap) }}"
                                    method="POST" class="inline"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-2"></i>
                                <p>Belum ada laporan lengkap{{ $search || $kelas ? ' yang sesuai dengan pencarian' : '' }}
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden space-y-4">
            @forelse ($laporan as $item)
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ $item->siswa->nama }}</h3>
                            <p class="text-sm text-gray-500">{{ $item->siswa->kelas->id_kelas ?? ' ' }}</p>
                        </div>
                        @if ($item->dikirim_ke_ortu)
                            <span
                                class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Terkirim</span>
                        @else
                            <span
                                class="inline-block px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-semibold">Draft</span>
                        @endif
                    </div>
                    <div class="mb-3 text-xs text-gray-600">
                        <i class="fas fa-calendar"></i> {{ $item->periode_mulai->format('d M') }} -
                        {{ $item->periode_selesai->format('d M Y') }}
                    </div>
                    <div class="mb-3 text-sm text-gray-600">
                        <p class="line-clamp-2">{{ $item->catatan_guru }}</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('guru.laporan-lengkap.show', $item->id_laporan_lengkap) }}"
                            class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-center text-sm transition">
                            <i class="fas fa-eye"></i> Lihat
                        </a>
                        <a href="{{ route('guru.laporan-lengkap.edit', $item->id_laporan_lengkap) }}"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-center text-sm transition">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('guru.laporan-lengkap.destroy', $item->id_laporan_lengkap) }}"
                            method="POST" class="flex-1"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm transition">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow p-12 text-center text-gray-500">
                    <i class="fas fa-inbox text-4xl mb-2"></i>
                    <p>Belum ada laporan lengkap{{ $search || $kelas ? ' yang sesuai dengan pencarian' : '' }}</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $laporan->links() }}
        </div>
    </div>
@endsection

@section('sidebar-menu')
    <x-sidebar-menu :guard="'guru'" :currentRoute="request()->route()->getName()" />
@endsection
