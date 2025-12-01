@extends('layouts.dashboard')

@section('title', 'Tambah Jadwal')
@section('nav-color', 'bg-blue-600')
@section('sidebar-color', 'bg-blue-600')
@section('dashboard-title', 'Tambah Jadwal')
@section('user-name', auth('admin')->user()->nama)
@section('user-role', 'Admin')

@section('content')
    <div class="p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Tambah Jadwal</h1>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('admin.jadwal.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="id_guru" class="block text-sm font-medium text-gray-700 mb-2">Guru <span
                            class="text-red-500">*</span></label>
                    <select id="id_guru" name="id_guru"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('id_guru') border-red-500 @enderror"
                        required>
                        <option value="">Pilih Guru</option>
                        @foreach ($guruList as $guru)
                            <option value="{{ $guru->id_guru }}" {{ old('id_guru') == $guru->id_guru ? 'selected' : '' }}>
                                {{ $guru->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_guru')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="id_mata_pelajaran" class="block text-sm font-medium text-gray-700 mb-2">Mata Pelajaran <span
                            class="text-red-500">*</span></label>
                    <select id="id_mata_pelajaran" name="id_mata_pelajaran"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('id_mata_pelajaran') border-red-500 @enderror"
                        required>
                        <option value="">Pilih Mata Pelajaran</option>
                        @foreach ($mataPelajaranList as $mapel)
                            <option value="{{ $mapel->id_mata_pelajaran }}"
                                {{ old('id_mata_pelajaran') == $mapel->id_mata_pelajaran ? 'selected' : '' }}>
                                {{ $mapel->nama_mapel }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_mata_pelajaran')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="ruang" class="block text-sm font-medium text-gray-700 mb-2">Ruang <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="ruang" name="ruang" value="{{ old('ruang') }}"
                        placeholder="Contoh: Kelas A, Lapangan dll"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('ruang') border-red-500 @enderror"
                        required>
                    @error('ruang')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="hari" class="block text-sm font-medium text-gray-700 mb-2">Hari <span
                            class="text-red-500">*</span></label>
                    <select id="hari" name="hari"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('hari') border-red-500 @enderror"
                        required>
                        <option value="">Pilih Hari</option>
                        <option value="Senin" {{ old('hari') == 'Senin' ? 'selected' : '' }}>Senin</option>
                        <option value="Selasa" {{ old('hari') == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                        <option value="Rabu" {{ old('hari') == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                        <option value="Kamis" {{ old('hari') == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                        <option value="Jumat" {{ old('hari') == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                        <option value="Sabtu" {{ old('hari') == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                    </select>
                    @error('hari')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="waktu_mulai" class="block text-sm font-medium text-gray-700 mb-2">Waktu Mulai <span
                                class="text-red-500">*</span></label>
                        <input type="time" id="waktu_mulai" name="waktu_mulai" value="{{ old('waktu_mulai') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('waktu_mulai') border-red-500 @enderror"
                            required>
                        @error('waktu_mulai')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="waktu_selesai" class="block text-sm font-medium text-gray-700 mb-2">Waktu Selesai <span
                                class="text-red-500">*</span></label>
                        <input type="time" id="waktu_selesai" name="waktu_selesai" value="{{ old('waktu_selesai') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('waktu_selesai') border-red-500 @enderror"
                            required>
                        @error('waktu_selesai')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="kelas" class="block text-sm font-medium text-gray-700 mb-2">Kelas <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="kelas" name="kelas" value="{{ old('kelas') }}"
                        placeholder="Contoh: Kelompok A1, Kelompok B2, dll"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('kelas') border-red-500 @enderror"
                        required>
                    @error('kelas')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai Semester
                        <span class="text-red-500">*</span></label>
                    <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tanggal_mulai') border-red-500 @enderror"
                        required>
                    <p class="text-sm text-gray-500 mt-1">
                        <i class="fas fa-info-circle"></i> Sistem akan otomatis membuat 14 pertemuan mingguan pada hari yang
                        dipilih
                    </p>
                    @error('tanggal_mulai')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                        Simpan
                    </button>
                    <a href="{{ route('admin.jadwal.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('sidebar-menu')
    <x-sidebar-menu :guard="'admin'" :currentRoute="request()->route()->getName()" />
@endsection
