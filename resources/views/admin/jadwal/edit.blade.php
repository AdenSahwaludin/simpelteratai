@extends('layouts.dashboard')

@section('title', 'Edit Jadwal')
@section('nav-color', 'bg-blue-600')
@section('sidebar-color', 'bg-blue-600')
@section('dashboard-title', 'Edit Jadwal')
@section('user-name', auth('admin')->user()->nama)
@section('user-role', 'Admin')

@section('content')
    <div class="p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Edit Jadwal</h1>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('admin.jadwal.update', $jadwal->id_jadwal) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">ID Jadwal</label>
                    <div class="px-4 py-2 bg-gray-100 rounded-lg text-gray-700">{{ $jadwal->id_jadwal }}</div>
                </div>

                <div class="mb-4">
                    <label for="id_guru" class="block text-sm font-medium text-gray-700 mb-2">Guru <span
                            class="text-red-500">*</span></label>
                    <select id="id_guru" name="id_guru"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('id_guru') border-red-500 @enderror"
                        required>
                        <option value="">Pilih Guru</option>
                        @foreach ($guruList as $guru)
                            <option value="{{ $guru->id_guru }}"
                                {{ old('id_guru', $jadwal->id_guru) == $guru->id_guru ? 'selected' : '' }}>
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
                                {{ old('id_mata_pelajaran', $jadwal->id_mata_pelajaran) == $mapel->id_mata_pelajaran ? 'selected' : '' }}>
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
                    <input type="text" id="ruang" name="ruang" value="{{ old('ruang', $jadwal->ruang) }}"
                        placeholder="Contoh: Kelas A, Lapangan dll"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('ruang') border-red-500 @enderror"
                        required>
                    @error('ruang')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="waktu_mulai" class="block text-sm font-medium text-gray-700 mb-2">Waktu Mulai <span
                                class="text-red-500">*</span></label>
                        <input type="time" id="waktu_mulai" name="waktu_mulai"
                            value="{{ old('waktu_mulai', $jadwal->waktu_mulai ? \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') : '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('waktu_mulai') border-red-500 @enderror"
                            required>
                        @error('waktu_mulai')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="waktu_selesai" class="block text-sm font-medium text-gray-700 mb-2">Waktu Selesai <span
                                class="text-red-500">*</span></label>
                        <input type="time" id="waktu_selesai" name="waktu_selesai"
                            value="{{ old('waktu_selesai', $jadwal->waktu_selesai ? \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') : '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('waktu_selesai') border-red-500 @enderror"
                            required>
                        @error('waktu_selesai')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                        Update
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
