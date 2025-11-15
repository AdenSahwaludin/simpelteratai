@extends('layouts.dashboard')

@section('title', 'Detail Guru')
@section('nav-color', 'bg-blue-600')
@section('sidebar-color', 'bg-blue-600')
@section('dashboard-title', 'Detail Guru')
@section('user-name', auth('admin')->user()->nama)
@section('user-role', 'Admin')

@section('sidebar-menu')
    <x-sidebar-menu :guard="'admin'" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Detail Data Guru</h2>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">ID Guru</label>
            <p class="text-gray-900">{{ $guru->id_guru }}</p>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
            <p class="text-gray-900">{{ $guru->nama }}</p>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
            <p class="text-gray-900">{{ $guru->email }}</p>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon</label>
            <p class="text-gray-900">{{ $guru->no_telpon }}</p>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Dibuat Pada</label>
            <p class="text-gray-900">{{ $guru->created_at->format('d/m/Y H:i') }}</p>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Terakhir Diperbarui</label>
            <p class="text-gray-900">{{ $guru->updated_at->format('d/m/Y H:i') }}</p>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('guru.edit', $guru->id_guru) }}"
                class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-2 rounded transition font-medium">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a href="{{ route('guru.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded transition font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
            <form action="{{ route('guru.destroy', $guru->id_guru) }}" method="POST" class="inline-block"
                onsubmit="return confirm('Apakah Anda yakin ingin menghapus guru ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded transition font-medium">
                    <i class="fas fa-trash mr-2"></i>Hapus
                </button>
            </form>
        </div>
    </div>
@endsection
