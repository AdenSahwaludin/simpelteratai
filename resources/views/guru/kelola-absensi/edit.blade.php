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
                <h2 class="text-2xl font-bold text-gray-800">Edit Absensi Siswa</h2>
                <p class="text-gray-600 mt-2">Perbarui data kehadiran siswa</p>
            </div>
            <a href="{{ route('guru.kelola-absensi.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-300">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('guru.kelola-absensi.update', $absensi->id_absensi) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Informasi Saat Ini</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500">ID Absensi</p>
                            <p class="font-medium text-gray-800">{{ $absensi->id_absensi }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Tanggal Asli</p>
                            <p class="font-medium text-gray-800">
                                {{ \Carbon\Carbon::parse($absensi->tanggal)->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="id_siswa" class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Siswa <span class="text-red-500">*</span>
                    </label>
                    <select name="id_siswa" id="id_siswa" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('id_siswa') border-red-500 @enderror">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach ($siswaList as $siswa)
                            <option value="{{ $siswa->id_siswa }}"
                                {{ old('id_siswa', $absensi->id_siswa) == $siswa->id_siswa ? 'selected' : '' }}>
                                {{ $siswa->nama }} ({{ $siswa->kelas }})
                            </option>
                        @endforeach
                    </select>
                    @error('id_siswa')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="id_jadwal" class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Jadwal <span class="text-red-500">*</span>
                    </label>
                    <select name="id_jadwal" id="id_jadwal" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('id_jadwal') border-red-500 @enderror">
                        <option value="">-- Pilih Jadwal --</option>
                        @foreach ($jadwalList as $jadwal)
                            <option value="{{ $jadwal->id_jadwal }}"
                                {{ old('id_jadwal', $absensi->id_jadwal) == $jadwal->id_jadwal ? 'selected' : '' }}>
                                {{ $jadwal->hari }} - {{ $jadwal->waktu }} - {{ $jadwal->mataPelajaran->nama_mapel }}
                                ({{ $jadwal->ruang }})
                            </option>
                        @endforeach
                    </select>
                    @error('id_jadwal')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal" id="tanggal" required
                        value="{{ old('tanggal', $absensi->tanggal) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('tanggal') border-red-500 @enderror">
                    @error('tanggal')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="status_kehadiran" class="block text-sm font-medium text-gray-700 mb-2">
                        Status Kehadiran <span class="text-red-500">*</span>
                    </label>
                    <select name="status_kehadiran" id="status_kehadiran" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('status_kehadiran') border-red-500 @enderror">
                        <option value="">-- Pilih Status --</option>
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

                <div class="mb-6">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                        Keterangan (Opsional)
                    </label>
                    <textarea name="keterangan" id="keterangan" rows="4" placeholder="Tambahkan keterangan jika diperlukan..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('keterangan') border-red-500 @enderror">{{ old('keterangan', $absensi->keterangan) }}</textarea>
                    @error('keterangan')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-4 justify-end">
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
@endsection
