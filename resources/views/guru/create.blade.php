@extends('layouts.dashboard')

@section('title', 'Tambah Guru')
@section('nav-color', 'bg-blue-600')
@section('sidebar-color', 'bg-blue-600')
@section('dashboard-title', 'Tambah Guru')
@section('user-name', auth('admin')->user()->nama)
@section('user-role', 'Admin')

@section('sidebar-menu')
    <x-sidebar-menu :guard="'admin'" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Tambah Guru Baru</h2>
        </div>

        <form action="{{ route('guru.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="id_guru" class="block text-sm font-medium text-gray-700 mb-2">ID Guru <span
                        class="text-red-500">*</span></label>
                <input type="text" name="id_guru" id="id_guru" value="{{ old('id_guru') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('id_guru') border-red-500 @enderror"
                    required maxlength="3" placeholder="Contoh: G01">
                @error('id_guru')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Maksimal 3 karakter</p>
            </div>

            <div class="mb-4">
                <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span
                        class="text-red-500">*</span></label>
                <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('nama') border-red-500 @enderror"
                    required maxlength="255">
                @error('nama')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email <span
                        class="text-red-500">*</span></label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                    required maxlength="150">
                @error('email')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="no_telpon" class="block text-sm font-medium text-gray-700 mb-2">No. Telepon <span
                        class="text-red-500">*</span></label>
                <input type="text" name="no_telpon" id="no_telpon" value="{{ old('no_telpon') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('no_telpon') border-red-500 @enderror"
                    required maxlength="15">
                @error('no_telpon')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password <span
                        class="text-red-500">*</span></label>
                <input type="password" name="password" id="password"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror"
                    required minlength="8">
                @error('password')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Minimal 8 karakter</p>
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi
                    Password <span class="text-red-500">*</span></label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                    required minlength="8">
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded transition font-medium">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
                <a href="{{ route('guru.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded transition font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </form>
    </div>
@endsection
