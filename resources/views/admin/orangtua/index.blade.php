@extends('layouts.dashboard')

@section('title', 'Data Orang Tua')
@section('nav-color', 'bg-blue-600')
@section('sidebar-color', 'bg-blue-600')
@section('dashboard-title', 'Data Orang Tua')
@section('user-name', auth('admin')->user()->nama)
@section('user-role', 'Admin')

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
            <h2 class="text-2xl font-bold text-gray-800">Daftar Orang Tua</h2>
            <a href="{{ route('admin.orangtua.create') }}"
                class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition font-medium text-center">
                <i class="fas fa-plus mr-2"></i>Tambah Orang Tua
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
            <form action="{{ route('admin.orangtua.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ $search }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Cari nama, ID, email, atau no telepon...">
                </div>
                <button type="submit"
                    class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition font-medium">
                    <i class="fas fa-search mr-2"></i>Cari
                </button>
                @if ($search)
                    <a href="{{ route('admin.orangtua.index') }}"
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
                            <x-sort-header column="id_orang_tua" label="ID" />
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                            <x-sort-header column="nama" label="Nama" />
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                            <x-sort-header column="email" label="Email" />
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                            <x-sort-header column="no_telpon" label="No Telepon" />
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah
                            Anak
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($orangTua as $item)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->id_orang_tua }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->nama }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->no_telpon }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-medium">
                                    {{ $item->siswa_count }} Anak
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <a href="{{ route('admin.orangtua.show', $item->id_orang_tua) }}"
                                    class="text-green-600 hover:text-green-900 mr-3">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                                <a href="{{ route('admin.orangtua.edit', $item->id_orang_tua) }}"
                                    class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('admin.orangtua.destroy', $item->id_orang_tua) }}" method="POST"
                                    class="inline-block"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus orang tua ini?')">
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
                                <p>Tidak ada data orang tua{{ $search ? ' yang sesuai dengan pencarian' : '' }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden space-y-4">
            @forelse ($orangTua as $item)
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $item->nama }}</h3>
                            <p class="text-sm text-gray-500">{{ $item->id_orang_tua }}</p>
                        </div>
                        <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-medium">
                            {{ $item->siswa_count }} Anak
                        </span>
                    </div>
                    <div class="space-y-2 mb-3 text-sm text-gray-600">
                        <p><i class="fas fa-envelope w-5"></i> {{ $item->email }}</p>
                        <p><i class="fas fa-phone w-5"></i> {{ $item->no_telpon }}</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.orangtua.show', $item->id_orang_tua) }}"
                            class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-center text-sm transition">
                            <i class="fas fa-eye"></i> Lihat
                        </a>
                        <a href="{{ route('admin.orangtua.edit', $item->id_orang_tua) }}"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-center text-sm transition">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.orangtua.destroy', $item->id_orang_tua) }}" method="POST"
                            class="flex-1" onsubmit="return confirm('Apakah Anda yakin ingin menghapus orang tua ini?')">
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
                    <p>Tidak ada data orang tua{{ $search ? ' yang sesuai dengan pencarian' : '' }}</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $orangTua->links() }}
        </div>
    </div>
@endsection

@section('sidebar-menu')
    <x-sidebar-menu :guard="'admin'" :currentRoute="request()->route()->getName()" />
@endsection
