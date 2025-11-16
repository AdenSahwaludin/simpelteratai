@extends('layouts.dashboard')

@section('nav-color', 'bg-blue-600')
@section('sidebar-color', 'bg-blue-600')
@section('user-name', auth('admin')->user()->nama)
@section('user-role', 'Admin')

@section('content')
    <div class="p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Edit Pengumuman</h1>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('admin.pengumuman.update', $pengumuman->id_pengumuman) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">ID Pengumuman</label>
                    <div class="px-4 py-2 bg-gray-100 rounded-lg text-gray-700">{{ $pengumuman->id_pengumuman }}</div>
                </div>

                <div class="mb-4">
                    <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">Judul <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="judul" name="judul" value="{{ old('judul', $pengumuman->judul) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('judul') border-red-500 @enderror"
                        required>
                    @error('judul')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="isi" class="block text-sm font-medium text-gray-700 mb-2">Isi <span
                            class="text-red-500">*</span></label>
                    <textarea id="isi" name="isi" rows="10"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('isi') border-red-500 @enderror"
                        required>{{ old('isi', $pengumuman->isi) }}</textarea>
                    @error('isi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">Tanggal <span
                            class="text-red-500">*</span></label>
                    <input type="date" id="tanggal" name="tanggal"
                        value="{{ old('tanggal', $pengumuman->tanggal->format('Y-m-d')) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tanggal') border-red-500 @enderror"
                        required>
                    @error('tanggal')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                        Update
                    </button>
                    <a href="{{ route('admin.pengumuman.index') }}"
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
