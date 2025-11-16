@extends('layouts.dashboard')

@section('title', 'Edit Mata Pelajaran')
@section('dashboard-title', 'Edit Mata Pelajaran')

@section('sidebar-menu')
    <x-sidebar-menu guard="admin" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
                <li><i class="fas fa-chevron-right text-xs"></i></li>
                <li><a href="{{ route('admin.mata-pelajaran.index') }}" class="hover:text-blue-600">Data Mata
                        Pelajaran</a></li>
                <li><i class="fas fa-chevron-right text-xs"></i></li>
                <li class="text-gray-900 font-medium">Edit Mata Pelajaran</li>
            </ol>
        </nav>

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Edit Mata Pelajaran</h1>
                <p class="text-gray-600 text-sm mt-1">Perbarui informasi mata pelajaran</p>
            </div>

            <form action="{{ route('admin.mata-pelajaran.update', $mataPelajaran->id_mata_pelajaran) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- ID Mata Pelajaran (Read Only) -->
                <div class="mb-6">
                    <label for="id_mata_pelajaran" class="block text-sm font-medium text-gray-700 mb-2">
                        ID Mata Pelajaran
                    </label>
                    <input type="text" id="id_mata_pelajaran" value="{{ $mataPelajaran->id_mata_pelajaran }}" disabled
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-600 cursor-not-allowed">
                </div>

                <!-- Nama Mata Pelajaran -->
                <div class="mb-6">
                    <label for="nama_mapel" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Mata Pelajaran <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nama_mapel" name="nama_mapel"
                        value="{{ old('nama_mapel', $mataPelajaran->nama_mapel) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama_mapel') border-red-500 @enderror"
                        placeholder="Contoh: Matematika">
                    @error('nama_mapel')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        <span>Update</span>
                    </button>
                    <a href="{{ route('admin.mata-pelajaran.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
