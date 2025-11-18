@extends('layouts.dashboard')

@section('title', 'Tambah Catatan Perilaku')
@section('nav-color', 'bg-green-600')
@section('sidebar-color', 'bg-green-600')
@section('dashboard-title', 'Tambah Catatan Perilaku')
@section('user-name', auth('guru')->user()->nama)
@section('user-role', 'Guru')

@section('sidebar-menu')
    <x-sidebar-menu :guard="'guru'" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Tambah Catatan Perilaku</h2>
                <p class="text-gray-600 mt-2">Catat perilaku siswa untuk pemantauan perkembangan</p>
            </div>
            <a href="{{ route('guru.catatan-perilaku.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-300">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('guru.catatan-perilaku.store') }}" method="POST">
                @csrf

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

                <div class="mb-6">
                    <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal" id="tanggal" required value="{{ old('tanggal', date('Y-m-d')) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('tanggal') border-red-500 @enderror">
                    @error('tanggal')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="catatan_perilaku" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan Perilaku <span class="text-red-500">*</span>
                    </label>
                    <textarea name="catatan_perilaku" id="catatan_perilaku" rows="5" required
                        placeholder="Tuliskan catatan perilaku siswa..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('catatan_perilaku') border-red-500 @enderror">{{ old('catatan_perilaku') }}</textarea>
                    @error('catatan_perilaku')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-4 justify-end">
                    <a href="{{ route('guru.catatan-perilaku.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition-colors duration-300">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition-colors duration-300">
                        <i class="fas fa-save mr-2"></i>Simpan Catatan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
