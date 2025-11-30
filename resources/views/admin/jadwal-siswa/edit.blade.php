@extends('layouts.dashboard')

@section('title', 'Kelola Siswa Jadwal')
@section('nav-color', 'bg-blue-600')
@section('sidebar-color', 'bg-blue-600')
@section('dashboard-title', 'Kelola Siswa Jadwal')
@section('user-name', auth('admin')->user()->nama)
@section('user-role', 'Admin')

@section('sidebar-menu')
    <x-sidebar-menu :guard="'admin'" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Kelola Siswa Jadwal</h2>
                <p class="text-gray-600 mt-2">Tambahkan atau hapus siswa dari jadwal ini</p>
            </div>
            <a href="{{ route('admin.jadwal.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-300">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Jadwal Info -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Jadwal</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-sm text-gray-600">ID Jadwal</p>
                    <p class="font-semibold text-gray-900">{{ $jadwal->id_jadwal }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Guru</p>
                    <p class="font-semibold text-gray-900">{{ $jadwal->guru->nama }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Mata Pelajaran</p>
                    <p class="font-semibold text-gray-900">{{ $jadwal->mataPelajaran->nama_mapel }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Ruang</p>
                    <p class="font-semibold text-gray-900">{{ $jadwal->ruang }}</p>
                </div>
            </div>
        </div>

        <!-- Form for selecting students -->
        <form action="{{ route('admin.jadwal-siswa.update', $jadwal->id_jadwal) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Students List by Class -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Daftar Siswa</h3>

                        @if ($siswaByClass->isEmpty())
                            <p class="text-gray-500 text-center py-8">Belum ada siswa terdaftar</p>
                        @else
                            @foreach ($siswaByClass as $kelas => $siswas)
                                <div class="mb-6">
                                    <h4 class="text-sm font-semibold text-blue-600 mb-3 px-3 py-2 bg-blue-50 rounded">
                                        Kelas {{ $kelas }} ({{ $siswas->count() }} siswa)
                                    </h4>
                                    <div class="space-y-2 pl-3">
                                        @foreach ($siswas as $siswa)
                                            <label
                                                class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded cursor-pointer">
                                                <input type="checkbox" name="siswa[]" value="{{ $siswa->id_siswa }}"
                                                    {{ in_array($siswa->id_siswa, $assignedSiswaIds) ? 'checked' : '' }}
                                                    class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                                                <span class="text-gray-800">{{ $siswa->nama }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan</h3>

                        <div class="space-y-4 mb-6">
                            <div class="text-center">
                                <p class="text-3xl font-bold text-blue-600" id="selectedCount">
                                    {{ count($assignedSiswaIds) }}
                                </p>
                                <p class="text-sm text-gray-600">Siswa Terpilih</p>
                            </div>
                            <div class="text-center">
                                <p class="text-3xl font-bold text-gray-400">{{ $siswaByClass->sum('count') }}</p>
                                <p class="text-sm text-gray-600">Total Siswa</p>
                            </div>
                        </div>

                        <div class="space-y-2 mb-6">
                            <button type="button" onclick="selectAll()"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                                <i class="fas fa-check-square mr-2"></i>Pilih Semua
                            </button>
                            <button type="button" onclick="deselectAll()"
                                class="w-full bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg transition">
                                <i class="fas fa-square mr-2"></i>Batal Pilih
                            </button>
                        </div>

                        <div class="border-t pt-4">
                            <button type="submit"
                                class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg transition font-semibold">
                                <i class="fas fa-save mr-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function selectAll() {
            document.querySelectorAll('input[name="siswa[]"]').forEach(checkbox => {
                checkbox.checked = true;
            });
            updateCount();
        }

        function deselectAll() {
            document.querySelectorAll('input[name="siswa[]"]').forEach(checkbox => {
                checkbox.checked = false;
            });
            updateCount();
        }

        function updateCount() {
            const checked = document.querySelectorAll('input[name="siswa[]"]:checked').length;
            document.getElementById('selectedCount').textContent = checked;
        }

        // Update count when checkboxes change
        document.querySelectorAll('input[name="siswa[]"]').forEach(checkbox => {
            checkbox.addEventListener('change', updateCount);
        });
    </script>
@endsection
