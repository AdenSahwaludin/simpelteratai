@extends('layouts.dashboard')

@section('title', 'Edit Jadwal Harian')
@section('nav-color', 'bg-blue-600')
@section('sidebar-color', 'bg-blue-600')
@section('dashboard-title', 'Edit Jadwal Harian')
@section('user-name', auth('admin')->user()->nama)
@section('user-role', 'Admin')

@section('sidebar-menu')
    <x-sidebar-menu :guard="'admin'" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="p-6">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Edit Jadwal Harian</h1>
                <p class="text-gray-600 mt-2">Perbarui jadwal kegiatan harian</p>
            </div>
            <a href="{{ route('admin.jadwal-harian.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-300">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('admin.jadwal-harian.update', $jadwal->id_jadwal_harian) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal" id="tanggal" required
                            value="{{ old('tanggal', $jadwal->tanggal->format('Y-m-d')) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('tanggal') border-red-500 @enderror">
                        @error('tanggal')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tema" class="block text-sm font-medium text-gray-700 mb-2">
                            Tema
                        </label>
                        <input type="text" name="tema" id="tema" value="{{ old('tema', $jadwal->tema) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('tema') border-red-500 @enderror">
                        @error('tema')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="waktu_mulai" class="block text-sm font-medium text-gray-700 mb-2">
                            Waktu Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="waktu_mulai" id="waktu_mulai" required
                            value="{{ old('waktu_mulai', $jadwal->waktu_mulai->format('H:i')) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('waktu_mulai') border-red-500 @enderror">
                        @error('waktu_mulai')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="waktu_selesai" class="block text-sm font-medium text-gray-700 mb-2">
                            Waktu Selesai <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="waktu_selesai" id="waktu_selesai" required
                            value="{{ old('waktu_selesai', $jadwal->waktu_selesai->format('H:i')) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('waktu_selesai') border-red-500 @enderror">
                        @error('waktu_selesai')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label for="kegiatan" class="block text-sm font-medium text-gray-700 mb-2">
                        Kegiatan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="kegiatan" id="kegiatan" rows="3" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('kegiatan') border-red-500 @enderror">{{ old('kegiatan', $jadwal->kegiatan) }}</textarea>
                    @error('kegiatan')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="kelas" class="block text-sm font-medium text-gray-700 mb-2">
                        Kelas (Opsional)
                    </label>
                    <input type="text" name="kelas" id="kelas" value="{{ old('kelas', $jadwal->kelas) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('kelas') border-red-500 @enderror">
                    @error('kelas')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan (Opsional)
                    </label>
                    <textarea name="catatan" id="catatan" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('catatan') border-red-500 @enderror">{{ old('catatan', $jadwal->catatan) }}</textarea>
                    @error('catatan')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-4 justify-end">
                    <a href="{{ route('admin.jadwal-harian.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition-colors duration-300">
                        Batal
                    </a>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors duration-300">
                        <i class="fas fa-save mr-2"></i>Update
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
