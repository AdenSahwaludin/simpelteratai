@extends('layouts.dashboard')

@section('title', 'Edit Absensi')
@section('nav-color', 'bg-green-600')
@section('sidebar-color', 'bg-green-600')
@section('dashboard-title', 'Edit Absensi')
@section('user-name', auth('guru')->user()->nama)
@section('user-role', 'Guru')

@section('sidebar-menu')
    <x-sidebar-menu :guard="'guru'" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Edit Absensi</h2>
                <p class="text-gray-600 mt-2">Perbarui status kehadiran siswa</p>
            </div>
            <a href="{{ route('guru.kelola-absensi.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-300">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <h3 class="text-sm font-semibold text-blue-900 mb-3">Informasi Absensi</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                    <div>
                        <p class="text-gray-600">ID Absensi</p>
                        <p class="font-semibold text-gray-900">{{ $absensi->id_absensi }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Siswa</p>
                        <p class="font-semibold text-gray-900">{{ $absensi->siswa->nama }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Kelas</p>
                        <p class="font-semibold text-gray-900">{{ $absensi->siswa->kelas->id_kelas ?? ' ' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Mata Pelajaran</p>
                        <p class="font-semibold text-gray-900">{{ $absensi->jadwal->mataPelajaran->nama_mapel }}</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('guru.kelola-absensi.update', $absensi->id_absensi) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Display-only fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal
                        </label>
                        <div class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700">
                            {{ $absensi->pertemuan->tanggal?->format('d M Y') ?? 'N/A' }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jadwal
                        </label>
                        <div class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700">
                            {{ $absensi->pertemuan->jadwal->mataPelajaran->nama_mapel ?? 'N/A' }}
                            ({{ $absensi->pertemuan->jadwal->ruang ?? 'N/A' }})
                        </div>
                    </div>
                </div>

                <!-- Editable field -->
                <div class="mb-6">
                    <label for="status_kehadiran" class="block text-sm font-medium text-gray-700 mb-2">
                        Status Kehadiran <span class="text-red-500">*</span>
                    </label>
                    <select name="status_kehadiran" id="status_kehadiran" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('status_kehadiran') border-red-500 @enderror"
                        onchange="updateStatusColor(this)">
                        <option value="hadir"
                            {{ old('status_kehadiran', $absensi->status_kehadiran) == 'hadir' ? 'selected' : '' }}>Hadir
                        </option>
                        <option value="izin"
                            {{ old('status_kehadiran', $absensi->status_kehadiran) == 'izin' ? 'selected' : '' }}>Izin
                        </option>
                        <option value="sakit"
                            {{ old('status_kehadiran', $absensi->status_kehadiran) == 'sakit' ? 'selected' : '' }}>Sakit
                        </option>
                        <option value="alpha"
                            {{ old('status_kehadiran', $absensi->status_kehadiran) == 'alpha' ? 'selected' : '' }}>Alpha
                        </option>
                    </select>
                    @error('status_kehadiran')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-4 justify-end border-t border-gray-200 pt-6">
                    <a href="{{ route('guru.kelola-absensi.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition-colors duration-300">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition-colors duration-300">
                        <i class="fas fa-save mr-2"></i>Perbarui Absensi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function updateStatusColor(selectElement) {
            const statusColors = {
                'hadir': 'border-green-500 bg-green-50 text-green-700',
                'izin': 'border-blue-500 bg-blue-50 text-blue-700',
                'sakit': 'border-yellow-500 bg-yellow-50 text-yellow-700',
                'alpha': 'border-red-500 bg-red-50 text-red-700',
            };

            // Remove all color classes
            Object.values(statusColors).forEach(classes => {
                classes.split(' ').forEach(cls => selectElement.classList.remove(cls));
            });

            // Add appropriate color
            if (selectElement.value && statusColors[selectElement.value]) {
                statusColors[selectElement.value].split(' ').forEach(cls => selectElement.classList.add(cls));
            }
        }

        // Apply color on page load
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('status_kehadiran');
            if (statusSelect) {
                updateStatusColor(statusSelect);
            }
        });
    </script>
@endsection
