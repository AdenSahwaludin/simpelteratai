@extends('layouts.dashboard')

@section('title', 'Kelola Absensi')
@section('nav-color', 'bg-green-600')
@section('sidebar-color', 'bg-green-600')
@section('dashboard-title', 'Kelola Absensi')
@section('user-name', auth('guru')->user()->nama)
@section('user-role', 'Guru')

@section('sidebar-menu')
    <x-sidebar-menu :guard="'guru'" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Kelola Absensi Siswa</h2>
                <p class="text-gray-600 mt-2">Kelola kehadiran siswa di kelas yang Anda ajar</p>
            </div>
            <a href="{{ route('guru.kelola-absensi.create') }}"
                class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition-colors duration-300">
                <i class="fas fa-plus-circle mr-2"></i>Tambah Absensi
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg mb-6">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form method="GET" action="{{ route('guru.kelola-absensi.index') }}"
                class="space-y-4 md:space-y-0 md:flex md:gap-4 md:flex-wrap md:items-end">
                <div class="flex-1 md:flex-none md:w-48">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari Siswa</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="Cari berdasarkan nama siswa..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
                <div class="w-full md:w-48">
                    <label for="id_jadwal" class="block text-sm font-medium text-gray-700 mb-2">Jadwal</label>
                    <select name="id_jadwal" id="id_jadwal"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Semua Jadwal</option>
                        @foreach ($jadwalList as $j)
                            <option value="{{ $j->id_jadwal }}"
                                {{ request('id_jadwal') == $j->id_jadwal ? 'selected' : '' }}>
                                {{ $j->mataPelajaran->nama_mapel }} ({{ $j->ruang }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full md:w-48">
                    <label for="kelas" class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                    <select name="kelas" id="kelas"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Semua Kelas</option>
                        @foreach ($kelasList as $k)
                            <option value="{{ $k }}" {{ request('kelas') == $k ? 'selected' : '' }}>
                                {{ $k }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full md:w-48">
                    <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal"
                        value="{{ request('tanggal', now()->toDateString()) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">

                </div>
                <div class="flex gap-2 w-full md:w-auto">
                    <button type="submit"
                        class="flex-1 md:flex-none bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors duration-300">
                        <i class="fas fa-search mr-2"></i>Cari
                    </button>
                    <a href="{{ route('guru.kelola-absensi.index') }}"
                        class="flex-1 md:flex-none bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors duration-300 text-center">
                        <i class="fas fa-redo mr-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            @if (!$hasFilter)
                <div class="p-12 text-center text-gray-500">
                    <i class="fas fa-search text-6xl mb-4 text-gray-300"></i>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Belum Ada Filter</h3>
                    <p>Silakan gunakan filter di atas untuk mencari data absensi siswa</p>
                </div>
            @elseif ($absensi && $absensi->isEmpty())
                <div class="p-12 text-center text-gray-500">
                    <i class="fas fa-calendar-check text-6xl mb-4 text-gray-300"></i>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Tidak Ada Data</h3>
                    <p>Tidak ada data absensi yang ditemukan untuk filter yang Anda pilih</p>
                </div>
            @elseif ($absensi && $absensi->count() > 0)
                <!-- Bulk Delete Form & UI -->
                <form id="bulkDeleteForm" action="{{ route('guru.kelola-absensi.bulk-destroy') }}" method="POST"
                    class="mb-4 px-6 py-4 bg-gray-50 border-b border-gray-200 hidden">
                    @csrf
                    <div class="flex justify-between items-center">
                        <div>
                            <span id="selectedCount" class="text-gray-700 font-semibold">0</span>
                            <span class="text-gray-600">absensi dipilih</span>
                            <input type="hidden" id="selectedIds" name="ids" value="">
                        </div>
                        <div class="flex gap-3">
                            <button type="button" id="cancelBulkDelete"
                                class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors duration-300">
                                <i class="fas fa-times mr-2"></i>Batal
                            </button>
                            <button type="button" id="confirmBulkDelete"
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors duration-300"
                                onclick="if (confirm('Apakah Anda yakin ingin menghapus absensi yang dipilih?')) { document.getElementById('bulkDeleteForm').submit(); }">
                                <i class="fas fa-trash mr-2"></i>Hapus
                            </button>
                        </div>
                    </div>
                </form>

                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <input type="checkbox" id="selectAll"
                                        class="w-4 h-4 rounded border-gray-300 cursor-pointer">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Siswa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jadwal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($absensi as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox"
                                            class="bulkCheckbox w-4 h-4 rounded border-gray-300 cursor-pointer"
                                            value="{{ $item->id_absensi }}" data-id="{{ $item->id_absensi }}">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->siswa->nama }}</div>
                                        <div class="text-xs text-gray-500">{{ $item->siswa->kelas }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $item->jadwal->mataPelajaran->nama_mapel ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'hadir' => 'bg-green-100 text-green-800',
                                                'izin' => 'bg-blue-100 text-blue-800',
                                                'sakit' => 'bg-yellow-100 text-yellow-800',
                                                'alpha' => 'bg-red-100 text-red-800',
                                            ];
                                        @endphp
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$item->status_kehadiran] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($item->status_kehadiran) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="flex gap-2">
                                            <a href="{{ route('guru.kelola-absensi.edit', $item->id_absensi) }}"
                                                class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('guru.kelola-absensi.destroy', $item->id_absensi) }}"
                                                method="POST" class="inline"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus absensi ini?')">
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

                <div class="md:hidden p-4 space-y-4">
                    @foreach ($absensi as $item)
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <div class="flex gap-3 mb-3">
                                <input type="checkbox"
                                    class="bulkCheckbox w-4 h-4 rounded border-gray-300 cursor-pointer mt-1"
                                    value="{{ $item->id_absensi }}" data-id="{{ $item->id_absensi }}">
                                <div class="flex-1">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $item->siswa->nama }}</p>
                                            <p class="text-sm text-gray-600">{{ $item->siswa->kelas }} •
                                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</p>
                                        </div>
                                        @php
                                            $statusColors = [
                                                'hadir' => 'bg-green-100 text-green-800',
                                                'izin' => 'bg-blue-100 text-blue-800',
                                                'sakit' => 'bg-yellow-100 text-yellow-800',
                                                'alpha' => 'bg-red-100 text-red-800',
                                            ];
                                        @endphp
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$item->status_kehadiran] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($item->status_kehadiran) }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-700 mb-3">
                                        {{ $item->jadwal->mataPelajaran->nama_mapel ?? '-' }}</p>
                                    <div class="flex gap-2">
                                        <a href="{{ route('guru.kelola-absensi.edit', $item->id_absensi) }}"
                                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition-colors duration-300 text-center">
                                            <i class="fas fa-edit mr-1"></i>Edit
                                        </a>
                                        <form action="{{ route('guru.kelola-absensi.destroy', $item->id_absensi) }}"
                                            method="POST" class="flex-1"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus absensi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm transition-colors duration-300">
                                                <i class="fas fa-trash mr-1"></i>Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $absensi->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const bulkCheckboxes = document.querySelectorAll('.bulkCheckbox');
            const bulkDeleteForm = document.getElementById('bulkDeleteForm');
            const selectedCount = document.getElementById('selectedCount');
            const selectedIds = document.getElementById('selectedIds');
            const cancelBulkDelete = document.getElementById('cancelBulkDelete');

            function updateBulkUI() {
                const checkedCount = document.querySelectorAll('.bulkCheckbox:checked').length;
                const checkedIds = Array.from(document.querySelectorAll('.bulkCheckbox:checked')).map(cb => cb
                    .value);

                selectedCount.textContent = checkedCount;
                selectedIds.value = JSON.stringify(checkedIds);

                if (checkedCount > 0) {
                    bulkDeleteForm.classList.remove('hidden');
                } else {
                    bulkDeleteForm.classList.add('hidden');
                }

                if (checkedCount === bulkCheckboxes.length) {
                    selectAllCheckbox.checked = true;
                    selectAllCheckbox.indeterminate = false;
                } else if (checkedCount > 0) {
                    selectAllCheckbox.indeterminate = true;
                } else {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = false;
                }
            }

            selectAllCheckbox.addEventListener('change', function() {
                bulkCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateBulkUI();
            });

            bulkCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateBulkUI);
            });

            cancelBulkDelete.addEventListener('click', function() {
                bulkCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
                updateBulkUI();
            });

            // Handle form submission - convert JSON string back to array
            bulkDeleteForm.addEventListener('submit', function(e) {
                const idsString = selectedIds.value;
                const idsArray = JSON.parse(idsString);
                selectedIds.value = idsArray;
            });
        });
    </script>
@endsection
