@extends('layouts.dashboard')

@section('title', 'Kelola Pengumuman')
@section('nav-color', 'bg-blue-600')
@section('sidebar-color', 'bg-blue-600')
@section('dashboard-title', 'Kelola Pengumuman')
@section('user-name', auth('admin')->user()->nama)
@section('user-role', 'Admin')

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
            <h2 class="text-2xl font-bold text-gray-800">Daftar Pengumuman</h2>
            <a href="{{ route('admin.pengumuman.create') }}"
                class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition font-medium text-center">
                <i class="fas fa-plus mr-2"></i>Tambah Pengumuman
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
            <form action="{{ route('admin.pengumuman.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ $search }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Cari judul atau isi pengumuman...">
                </div>
                <button type="submit"
                    class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition font-medium">
                    <i class="fas fa-search mr-2"></i>Cari
                </button>
                @if ($search)
                    <a href="{{ route('admin.pengumuman.index') }}"
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
                            <x-sort-header column="id_pengumuman" label="ID" />
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                            <x-sort-header column="judul" label="Judul" />
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Isi</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                            <x-sort-header column="tanggal" label="Tanggal" />
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pembuat
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($pengumuman as $item)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->id_pengumuman }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->judul }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <div class="max-w-xs truncate">{{ $item->isi }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->admin->nama }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <a href="{{ route('admin.pengumuman.show', $item->id_pengumuman) }}"
                                    class="text-green-600 hover:text-green-900 mr-3">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                                <a href="{{ route('admin.pengumuman.edit', $item->id_pengumuman) }}"
                                    class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('admin.pengumuman.destroy', $item->id_pengumuman) }}" method="POST"
                                    class="inline-block"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')">
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
                                <p>Tidak ada data pengumuman{{ $search ? ' yang sesuai dengan pencarian' : '' }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden space-y-4">
            @forelse ($pengumuman as $item)
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ $item->judul }}</h3>
                            <p class="text-sm text-gray-500">{{ $item->id_pengumuman }}</p>
                        </div>
                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium ml-2">
                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d M') }}
                        </span>
                    </div>
                    <div class="mb-3 text-sm text-gray-600">
                        <p class="line-clamp-2">{{ $item->isi }}</p>
                    </div>
                    <div class="mb-3 text-xs text-gray-500">
                        <i class="fas fa-user"></i> {{ $item->admin->nama }}
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.pengumuman.show', $item->id_pengumuman) }}"
                            class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-center text-sm transition">
                            <i class="fas fa-eye"></i> Lihat
                        </a>
                        <a href="{{ route('admin.pengumuman.edit', $item->id_pengumuman) }}"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-center text-sm transition">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.pengumuman.destroy', $item->id_pengumuman) }}" method="POST"
                            class="flex-1" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')">
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
                    <p>Tidak ada data pengumuman{{ $search ? ' yang sesuai dengan pencarian' : '' }}</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $pengumuman->links() }}
        </div>
    </div>
@endsection

@section('sidebar-menu')
    <x-sidebar-menu :guard="'admin'" :currentRoute="request()->route()->getName()" />
@endsection
