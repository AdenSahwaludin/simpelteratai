@extends('layouts.dashboard')

@section('title', 'Kelola Jadwal')
@section('nav-color', 'bg-blue-600')
@section('sidebar-color', 'bg-blue-600')
@section('dashboard-title', 'Kelola Jadwal')
@section('user-name', auth('admin')->user()->nama)
@section('user-role', 'Admin')

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
            <h2 class="text-2xl font-bold text-gray-800">Daftar Jadwal</h2>
            <a href="{{ route('admin.jadwal.create') }}"
                class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition font-medium text-center">
                <i class="fas fa-plus mr-2"></i>Tambah Jadwal
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow p-4 mb-4">
            <form action="{{ route('admin.jadwal.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ $search }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Cari guru, mata pelajaran, atau ruang...">
                </div>
                <div class="w-full md:w-48">
                    <select name="guru"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Guru</option>
                        @foreach ($guruList as $g)
                            <option value="{{ $g->id_guru }}" {{ $guru == $g->id_guru ? 'selected' : '' }}>
                                {{ $g->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit"
                    class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition font-medium">
                    <i class="fas fa-search mr-2"></i>Cari
                </button>
                @if ($search || $guru)
                    <a href="{{ route('admin.jadwal.index') }}"
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
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                            <x-sort-header column="id_jadwal" label="ID" />
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guru</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata
                            Pelajaran
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                            <x-sort-header column="ruang" label="Ruang" />
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                            <x-sort-header column="waktu" label="Waktu" />
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($jadwal as $item)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->id_jadwal }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $item->guru->nama }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $item->mataPelajaran->nama_mapel }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->ruang }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <span class="px-2 py-1 bg-teal-100 text-teal-800 rounded-full text-xs font-medium">
                                    {{ $item->waktu }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <a href="{{ route('admin.jadwal.show', $item->id_jadwal) }}"
                                    class="text-green-600 hover:text-green-900 mr-3">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                                <a href="{{ route('admin.jadwal.edit', $item->id_jadwal) }}"
                                    class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('admin.jadwal.destroy', $item->id_jadwal) }}" method="POST"
                                    class="inline-block"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-2"></i>
                                <p>Tidak ada data jadwal{{ $search || $guru ? ' yang sesuai dengan pencarian' : '' }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden space-y-4">
            @forelse ($jadwal as $item)
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $item->mataPelajaran->nama_mapel }}</h3>
                            <p class="text-sm text-gray-500">{{ $item->id_jadwal }}</p>
                        </div>
                        <span class="px-2 py-1 bg-teal-100 text-teal-800 rounded-full text-xs font-medium">
                            {{ $item->waktu }}
                        </span>
                    </div>
                    <div class="space-y-2 mb-3 text-sm text-gray-600">
                        <p><i class="fas fa-user w-5"></i> {{ $item->guru->nama }}</p>
                        <p><i class="fas fa-door-open w-5"></i> Ruang {{ $item->ruang }}</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.jadwal.show', $item->id_jadwal) }}"
                            class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-center text-sm transition">
                            <i class="fas fa-eye"></i> Lihat
                        </a>
                        <a href="{{ route('admin.jadwal.edit', $item->id_jadwal) }}"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-center text-sm transition">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.jadwal.destroy', $item->id_jadwal) }}" method="POST" class="flex-1"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm transition">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow p-12 text-center text-gray-500">
                    <i class="fas fa-inbox text-4xl mb-2"></i>
                    <p>Tidak ada data jadwal{{ $search || $guru ? ' yang sesuai dengan pencarian' : '' }}</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $jadwal->links() }}
        </div>
    </div>
@endsection

@section('sidebar-menu')
    <x-sidebar-menu :guard="'admin'" :currentRoute="request()->route()->getName()" />
@endsection
