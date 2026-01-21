@extends('layouts.dashboard')

@section('title', 'Edit Siswa')
@section('nav-color', 'bg-blue-600')
@section('sidebar-color', 'bg-blue-600')
@section('dashboard-title', 'Edit Siswa')
@section('user-name', auth('admin')->user()->nama)
@section('user-role', 'Admin')

@section('sidebar-menu')
    <x-sidebar-menu :guard="'admin'" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="mb-4">
            <a href="{{ route('admin.siswa.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i>
                <span>Kembali ke Daftar Siswa</span>
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 md:p-8">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-user-edit text-blue-600 mr-3"></i>
                    Edit Data Siswa
                </h2>
                <p class="text-sm text-gray-600 mt-1">Perbarui informasi siswa di bawah ini</p>
            </div>

            <form action="{{ route('admin.siswa.update', $siswa->id_siswa) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <label class="block text-sm font-medium text-blue-900 mb-2">ID Siswa</label>
                    <div class="flex items-center">
                        <i class="fas fa-id-card text-blue-600 mr-3"></i>
                        <span class="text-lg font-semibold text-blue-800">{{ $siswa->id_siswa }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Siswa <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama', $siswa->nama) }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama') border-red-500 @enderror"
                            required autofocus>
                        @error('nama')
                            <p class="text-red-500 text-xs mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis Kelamin <span class="text-gray-400 text-xs">(Opsional)</span>
                        </label>
                        <select name="jenis_kelamin" id="jenis_kelamin"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('jenis_kelamin') border-red-500 @enderror">
                            <option value="">-- Pilih --</option>
                            <option value="L"
                                {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki
                            </option>
                            <option value="P"
                                {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan
                            </option>
                        </select>
                        @error('jenis_kelamin')
                            <p class="text-red-500 text-xs mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 mb-2">
                            Tempat Lahir <span class="text-gray-400 text-xs">(Opsional)</span>
                        </label>
                        <input type="text" name="tempat_lahir" id="tempat_lahir"
                            value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tempat_lahir') border-red-500 @enderror">
                        @error('tempat_lahir')
                            <p class="text-red-500 text-xs mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Lahir <span class="text-gray-400 text-xs">(Opsional)</span>
                        </label>
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                            value="{{ old('tanggal_lahir', $siswa->tanggal_lahir) }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tanggal_lahir') border-red-500 @enderror">
                        @error('tanggal_lahir')
                            <p class="text-red-500 text-xs mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="id_kelas" class="block text-sm font-medium text-gray-700 mb-2">
                            Kelas <span class="text-red-500">*</span>
                        </label>
                        <select name="id_kelas" id="id_kelas"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('id_kelas') border-red-500 @enderror"
                            required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($kelasList as $kelas)
                                <option value="{{ $kelas->id_kelas }}"
                                    {{ old('id_kelas', $siswa->id_kelas) == $kelas->id_kelas ? 'selected' : '' }}>
                                    {{ $kelas->id_kelas }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_kelas')
                            <p class="text-red-500 text-xs mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">
                        Alamat <span class="text-red-500">*</span>
                    </label>
                    <textarea name="alamat" id="alamat" rows="3"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('alamat') border-red-500 @enderror"
                        required>{{ old('alamat', $siswa->alamat) }}</textarea>
                    @error('alamat')
                        <p class="text-red-500 text-xs mt-1 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="id_orang_tua" class="block text-sm font-medium text-gray-700 mb-2">
                        Orang Tua <span class="text-red-500">*</span>
                    </label>
                    <select name="id_orang_tua" id="id_orang_tua"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('id_orang_tua') border-red-500 @enderror"
                        required>
                        <option value="">-- Pilih Orang Tua --</option>
                        @foreach ($orangTuaList as $orangTua)
                            <option value="{{ $orangTua->id_orang_tua }}"
                                {{ old('id_orang_tua', $siswa->id_orang_tua) == $orangTua->id_orang_tua ? 'selected' : '' }}>
                                {{ $orangTua->nama }} ({{ $orangTua->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('id_orang_tua')
                        <p class="text-red-500 text-xs mt-1 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200">
                    <button type="submit"
                        class="flex-1 sm:flex-none bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg transition font-medium shadow-sm hover:shadow-md">
                        <i class="fas fa-save mr-2"></i>Update Data
                    </button>
                    <a href="{{ route('admin.siswa.index') }}"
                        class="flex-1 sm:flex-none text-center bg-gray-500 hover:bg-gray-600 text-white px-8 py-3 rounded-lg transition font-medium">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
