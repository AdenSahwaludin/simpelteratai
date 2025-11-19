@extends('layouts.dashboard')

@section('title', 'Tambah Nilai')
@section('nav-color', 'bg-green-600')
@section('sidebar-color', 'bg-green-600')
@section('dashboard-title', 'Tambah Nilai')
@section('user-name', auth('guru')->user()->nama)
@section('user-role', 'Guru')

@section('sidebar-menu')
    <x-sidebar-menu :guard="'guru'" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Page Header with Back Button -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Tambah Nilai Siswa</h2>
                <p class="text-gray-600 mt-2">Masukkan nilai siswa untuk mata pelajaran yang Anda ajar</p>
            </div>
            <a href="{{ route('guru.input-nilai.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-300">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('guru.input-nilai.store') }}" method="POST">
                @csrf

                <!-- Siswa Selection -->
                <div class="mb-6">
                    <label for="id_siswa" class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Siswa <span class="text-red-500">*</span>
                    </label>
                    <select name="id_siswa" id="id_siswa" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('id_siswa') border-red-500 @enderror">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach ($siswaList as $siswa)
                            <option value="{{ $siswa->id_siswa }}"
                                {{ old('id_siswa', request('id_siswa')) == $siswa->id_siswa ? 'selected' : '' }}>
                                {{ $siswa->nama }} ({{ $siswa->kelas }})
                            </option>
                        @endforeach
                    </select>
                    @error('id_siswa')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mata Pelajaran Selection -->
                <div class="mb-6">
                    <label for="id_mata_pelajaran" class="block text-sm font-medium text-gray-700 mb-2">
                        Mata Pelajaran <span class="text-red-500">*</span>
                    </label>
                    <select name="id_mata_pelajaran" id="id_mata_pelajaran" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('id_mata_pelajaran') border-red-500 @enderror">
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        @foreach ($mataPelajaranList as $mapel)
                            <option value="{{ $mapel->id_mata_pelajaran }}"
                                {{ old('id_mata_pelajaran') == $mapel->id_mata_pelajaran ? 'selected' : '' }}>
                                {{ $mapel->nama_mapel }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_mata_pelajaran')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nilai Input -->
                <div class="mb-6">
                    <label for="nilai" class="block text-sm font-medium text-gray-700 mb-2">
                        Nilai (0-100) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="nilai" id="nilai" min="0" max="100" required
                        value="{{ old('nilai') }}" placeholder="Masukkan nilai antara 0-100"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('nilai') border-red-500 @enderror">
                    @error('nilai')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Komentar Input -->
                <div class="mb-6">
                    <label for="komentar" class="block text-sm font-medium text-gray-700 mb-2">
                        Komentar (Opsional)
                    </label>
                    <textarea name="komentar" id="komentar" rows="4" placeholder="Tambahkan komentar tentang nilai siswa..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('komentar') border-red-500 @enderror">{{ old('komentar') }}</textarea>
                    @error('komentar')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex gap-4 justify-end">
                    <a href="{{ route('guru.input-nilai.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition-colors duration-300">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition-colors duration-300">
                        <i class="fas fa-save mr-2"></i>Simpan Nilai
                    </button>
                </div>
            </form>
        </div>

        <!-- Instructions Card -->
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg mt-6">
            <div class="flex">
                <div class="flex shrink-0">
                    <i class="fas fa-info-circle text-blue-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        <strong>Catatan:</strong> Nilai harus berupa angka antara 0 hingga 100. Pastikan Anda memilih siswa
                        dan mata pelajaran yang tepat sebelum menyimpan.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
