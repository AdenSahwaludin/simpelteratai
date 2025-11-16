@extends('layouts.dashboard')

@section('title', 'Detail Komentar')
@section('dashboard-title', 'Detail Komentar')

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
                <li class="text-gray-900 font-medium">Detail</li>
            </ol>
        </nav>

        <!-- Detail Card -->
        <div class="bg-white rounded-lg shadow-md p-6 max-w-3xl">
            <!-- Header -->
            <div class="flex items-start justify-between mb-6 pb-4 border-b">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Detail Komentar</h1>
                    <div class="flex items-center gap-2 text-sm text-gray-600 mt-2">
                        <i class="fas fa-clock"></i>
                        <span>{{ $komentar->created_at->format('d F Y, H:i') }}</span>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('orangtua.komentar.edit', $komentar->id_komentar) }}"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-edit"></i>
                        <span>Edit</span>
                    </a>
                    <form action="{{ route('orangtua.komentar.destroy', $komentar->id_komentar) }}" method="POST"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus komentar ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition flex items-center gap-2">
                            <i class="fas fa-trash"></i>
                            <span>Hapus</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Content -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Isi Komentar:</label>
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <p class="text-gray-800 whitespace-pre-wrap leading-relaxed">{{ $komentar->komentar }}</p>
                </div>
            </div>

            <!-- Information -->
            <div class="bg-blue-50 p-4 rounded-lg mb-6">
                <h3 class="font-semibold text-gray-800 mb-2">Informasi:</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                    <div>
                        <span class="text-gray-600">ID Komentar:</span>
                        <span class="font-medium text-gray-800 ml-2">{{ $komentar->id_komentar }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Dibuat:</span>
                        <span class="font-medium text-gray-800 ml-2">{{ $komentar->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @if ($komentar->updated_at != $komentar->created_at)
                        <div class="md:col-span-2">
                            <span class="text-gray-600">Terakhir Diubah:</span>
                            <span class="font-medium text-gray-800 ml-2">{{ $komentar->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Back Button -->
            <div>
                <a href="{{ route('orangtua.komentar.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition inline-flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Daftar</span>
                </a>
            </div>
        </div>
    </div>
@endsection
