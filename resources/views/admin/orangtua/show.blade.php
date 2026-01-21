@extends('layouts.dashboard')

@section('title', 'Detail Orang Tua')
@section('nav-color', 'bg-blue-600')
@section('sidebar-color', 'bg-blue-600')
@section('dashboard-title', 'Detail Orang Tua')
@section('user-name', auth('admin')->user()->nama)
@section('user-role', 'Admin')

@section('sidebar-menu')
    <x-sidebar-menu :guard="'admin'" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="mb-4">
            <a href="{{ route('admin.orangtua.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i>
                <span>Kembali ke Daftar Orang Tua</span>
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Header dengan Info Dasar -->
            <div class="bg-linear-to-r from-blue-600 to-blue-700 px-6 py-8 text-white">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="inline-block px-3 py-1 bg-white text-blue-600 rounded-full font-bold text-lg">
                                {{ $orangTua->id_orang_tua }}
                            </span>
                        </div>
                        <h1 class="text-3xl font-bold">{{ $orangTua->nama }}</h1>
                        <p class="text-blue-100 mt-2">
                            <i class="fas fa-children mr-2"></i>
                            {{ $orangTua->siswa->count() }} Anak
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.orangtua.edit', $orangTua->id_orang_tua) }}"
                            class="inline-flex items-center px-4 py-2 bg-white text-blue-600 hover:bg-gray-100 rounded-lg transition font-medium">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </a>
                        <form action="{{ route('admin.orangtua.destroy', $orangTua->id_orang_tua) }}" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus orang tua {{ $orangTua->nama }}?')"
                            class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition font-medium">
                                <i class="fas fa-trash mr-2"></i>Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Konten Detail -->
            <div class="p-6 md:p-8">
                <!-- Informasi Kontak -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-address-card text-blue-600 mr-3"></i>
                        Informasi Kontak
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="border border-gray-200 rounded-lg p-4">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide flex items-center">
                                <i class="fas fa-envelope mr-2 text-blue-600"></i>Email
                            </label>
                            <p class="text-lg text-gray-800 font-medium mt-2">
                                <a href="mailto:{{ $orangTua->email }}" class="text-blue-600 hover:underline">
                                    {{ $orangTua->email }}
                                </a>
                            </p>
                        </div>
                        <div class="border border-gray-200 rounded-lg p-4">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide flex items-center">
                                <i class="fas fa-phone mr-2 text-blue-600"></i>Nomor Telepon
                            </label>
                            <p class="text-lg text-gray-800 font-medium mt-2">
                                <a href="tel:{{ $orangTua->no_telpon }}" class="text-blue-600 hover:underline">
                                    {{ $orangTua->no_telpon }}
                                </a>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Daftar Anak -->
                @if ($orangTua->siswa->isNotEmpty())
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-children text-blue-600 mr-3"></i>
                            Daftar Anak
                        </h2>
                        <div class="overflow-x-auto">
                            <table class="w-full border-collapse">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th
                                            class="border border-gray-200 px-4 py-2 text-left text-sm font-semibold text-gray-700">
                                            ID Siswa</th>
                                        <th
                                            class="border border-gray-200 px-4 py-2 text-left text-sm font-semibold text-gray-700">
                                            Nama Siswa</th>
                                        <th
                                            class="border border-gray-200 px-4 py-2 text-left text-sm font-semibold text-gray-700">
                                            Kelas</th>
                                        <th
                                            class="border border-gray-200 px-4 py-2 text-left text-sm font-semibold text-gray-700">
                                            Jenis Kelamin</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orangTua->siswa as $siswa)
                                        <tr class="hover:bg-gray-50">
                                            <td class="border border-gray-200 px-4 py-3 text-gray-800 font-medium">
                                                <a href="{{ route('admin.siswa.show', $siswa->id_siswa) }}"
                                                    class="text-blue-600 hover:underline">
                                                    {{ $siswa->id_siswa }}
                                                </a>
                                            </td>
                                            <td class="border border-gray-200 px-4 py-3 text-gray-800">
                                                {{ $siswa->nama }}
                                            </td>
                                            <td class="border border-gray-200 px-4 py-3 text-gray-800">
                                                <span
                                                    class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                                    {{ $siswa->kelas->id_kelas ?? ' ' }}
                                                </span>
                                            </td>
                                            <td class="border border-gray-200 px-4 py-3 text-gray-800">
                                                <span
                                                    class="inline-block px-3 py-1 {{ $siswa->jenis_kelamin == 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }} rounded text-sm font-medium">
                                                    {{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="border border-yellow-200 bg-yellow-50 rounded-lg p-4 text-yellow-800 mb-8">
                        <i class="fas fa-info-circle mr-2"></i>
                        Orang tua ini belum memiliki anak yang terdaftar
                    </div>
                @endif

                <!-- Metadata -->
                <div class="border-t border-gray-200 pt-6 text-xs text-gray-500">
                    <p>
                        <i class="fas fa-calendar-plus mr-2"></i>
                        Dibuat pada: {{ $orangTua->created_at->translatedFormat('d F Y H:i') }}
                    </p>
                    <p>
                        <i class="fas fa-calendar-check mr-2"></i>
                        Diperbarui pada: {{ $orangTua->updated_at->translatedFormat('d F Y H:i') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
