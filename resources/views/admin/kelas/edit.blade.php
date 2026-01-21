@extends('layouts.dashboard')

@section('title', 'Edit Wali Kelas')
@section('nav-color', 'bg-blue-600')
@section('sidebar-color', 'bg-blue-600')
@section('dashboard-title', 'Edit Wali Kelas')
@section('user-name', auth('admin')->user()->nama)
@section('user-role', 'Admin')

@section('sidebar-menu')
    <x-sidebar-menu :guard="'admin'" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.kelas.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i>
                <span>Kembali ke Daftar Kelas</span>
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-school text-blue-600 mr-3"></i>
                    Edit Wali Kelas: {{ $kelas->id_kelas }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Kelola penugasan wali kelas</p>
            </div>

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <p class="font-semibold">Terjadi Kesalahan:</p>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.kelas.update', $kelas) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label for="id_kelas" class="block text-sm font-medium text-gray-700 mb-2">
                        ID Kelas
                    </label>
                    <input type="text" id="id_kelas" value="{{ $kelas->id_kelas }}" disabled
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-700">
                </div>

                <div class="mb-6">
                    <label for="id_guru_wali" class="block text-sm font-medium text-gray-700 mb-2">
                        Wali Kelas
                    </label>
                    <select id="id_guru_wali" name="id_guru_wali"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Belum ditugaskan --</option>
                        @foreach ($guru as $g)
                            <option value="{{ $g->id_guru }}" @selected($kelas->id_guru_wali === $g->id_guru)>
                                {{ $g->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_guru_wali')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-users mr-2"></i>Siswa di Kelas ({{ $kelas->siswa()->count() }})
                    </label>
                    <div class="bg-gray-50 rounded-lg p-4 max-h-64 overflow-y-auto">
                        @if ($kelas->siswa()->count() > 0)
                            <ul class="space-y-2">
                                @foreach ($kelas->siswa()->get() as $siswa)
                                    <li class="flex items-center text-sm text-gray-700 p-2 hover:bg-gray-100 rounded">
                                        <i class="fas fa-user-circle mr-2 text-blue-600"></i>
                                        {{ $siswa->id_siswa }} - {{ $siswa->nama }}
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm text-gray-500 text-center py-4">
                                <i class="fas fa-inbox text-2xl mb-2"></i>
                            <p>Tidak ada siswa di kelas ini</p>
                            </p>
                        @endif
                    </div>
                </div>

                <div class="flex gap-3 pt-4 border-t border-gray-200">
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                    <a href="{{ route('admin.kelas.index') }}"
                        class="px-6 py-2 bg-gray-300 text-gray-900 rounded-lg hover:bg-gray-400 transition flex items-center">
                        <i class="fas fa-times mr-2"></i>
                        <span>Batal</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
