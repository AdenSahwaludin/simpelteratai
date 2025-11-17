@extends('layouts.dashboard')

@section('title', 'Edit Komentar')
@section('dashboard-title', 'Edit Komentar')
@section('nav-color', 'bg-purple-600')
@section('sidebar-color', 'bg-purple-600')
@section('user-name', auth('orangtua')->user()->nama)
@section('user-role', 'Orang Tua')

@section('sidebar-menu')
    <x-sidebar-menu guard="orangtua" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('orangtua.dashboard') }}" class="hover:text-purple-600">Dashboard</a></li>
                <li><i class="fas fa-chevron-right text-xs"></i></li>
                <li><a href="{{ route('orangtua.komentar.index') }}" class="hover:text-purple-600">Komentar</a></li>
                <li><i class="fas fa-chevron-right text-xs"></i></li>
                <li class="text-gray-900 font-medium">Edit Komentar</li>
            </ol>
        </nav>

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Edit Komentar</h1>
                <p class="text-gray-600 text-sm mt-1">Perbarui komentar Anda</p>
            </div>

            <form action="{{ route('orangtua.komentar.update', $komentar->id_komentar) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Komentar -->
                <div class="mb-6">
                    <label for="komentar" class="block text-sm font-medium text-gray-700 mb-2">
                        Komentar <span class="text-red-500">*</span>
                    </label>
                    <textarea id="komentar" name="komentar" rows="8" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('komentar') border-red-500 @enderror"
                        placeholder="Tulis komentar Anda...">{{ old('komentar', $komentar->komentar) }}</textarea>
                    @error('komentar')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <button type="submit"
                        class="bg-purple-500 hover:bg-purple-600 text-white px-6 py-2 rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        <span>Update</span>
                    </button>
                    <a href="{{ route('orangtua.komentar.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
