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
    <div class="max-w-5xl mx-auto">
        <div class="mb-4">
            <a href="{{ route('admin.guru.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i>
                <span>Kembali ke Daftar Guru</span>
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Header dengan Info Dasar -->
            <div class="bg-linear-to-r from-blue-600 to-blue-700 px-6 py-8 text-white">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="inline-block px-3 py-1 bg-white text-blue-600 rounded-full font-bold text-lg">
                                {{ $guru->id_guru }}
                            </span>
                        </div>
                        <h1 class="text-3xl font-bold">{{ $guru->nama }}</h1>
                        <p class="text-blue-100 mt-2">
                            <i class="fas fa-chalkboard mr-2"></i>
                            {{ $guru->jadwal->count() }} Jadwal Mengajar
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.guru.edit', $guru->id_guru) }}"
                            class="inline-flex items-center px-4 py-2 bg-white text-blue-600 hover:bg-gray-100 rounded-lg transition font-medium">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </a>
                        <form action="{{ route('admin.guru.destroy', $guru->id_guru) }}" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus guru {{ $guru->nama }}?')"
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
                                <i class="fas fa-id-card mr-2 text-blue-600"></i>NIP
                            </label>
                            <p class="text-lg text-gray-800 font-medium mt-2">
                                {{ $guru->nip }}
                            </p>
                        </div>
                        <div class="border border-gray-200 rounded-lg p-4">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide flex items-center">
                                <i class="fas fa-envelope mr-2 text-blue-600"></i>Email
                            </label>
                            <p class="text-lg text-gray-800 font-medium mt-2">
                                <a href="mailto:{{ $guru->email }}" class="text-blue-600 hover:underline">
                                    {{ $guru->email }}
                                </a>
                            </p>
                        </div>
                        <div class="border border-gray-200 rounded-lg p-4">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide flex items-center">
                                <i class="fas fa-phone mr-2 text-blue-600"></i>Nomor Telepon
                            </label>
                            <p class="text-lg text-gray-800 font-medium mt-2">
                                <a href="tel:{{ $guru->no_telpon }}" class="text-blue-600 hover:underline">
                                    {{ $guru->no_telpon }}
                                </a>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Jadwal Mengajar -->
                @if ($guru->jadwal->isNotEmpty())
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-calendar-alt text-blue-600 mr-3"></i>
                            Jadwal Mengajar
                        </h2>
                        <div class="overflow-x-auto">
                            <table class="w-full border-collapse">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th
                                            class="border border-gray-200 px-4 py-2 text-left text-sm font-semibold text-gray-700">
                                            ID Jadwal</th>
                                        <th
                                            class="border border-gray-200 px-4 py-2 text-left text-sm font-semibold text-gray-700">
                                            Mata Pelajaran</th>
                                        <th
                                            class="border border-gray-200 px-4 py-2 text-left text-sm font-semibold text-gray-700">
                                            Waktu</th>
                                        <th
                                            class="border border-gray-200 px-4 py-2 text-left text-sm font-semibold text-gray-700">
                                            Ruang</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($guru->jadwal as $jadwal)
                                        <tr class="hover:bg-gray-50">
                                            <td class="border border-gray-200 px-4 py-3 text-gray-800 font-medium">
                                                {{ $jadwal->id_jadwal }}
                                            </td>
                                            <td class="border border-gray-200 px-4 py-3 text-gray-800">
                                                <span
                                                    class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                                    {{ $jadwal->mataPelajaran->nama_mapel }}
                                                </span>
                                            </td>
                                            <td class="border border-gray-200 px-4 py-3 text-gray-800">
                                                <i class="fas fa-clock text-blue-600 mr-2"></i>
                                                @if ($jadwal->waktu_mulai && $jadwal->waktu_selesai)
                                                    {{ $jadwal->waktu_mulai->format('H:i') }} -
                                                    {{ $jadwal->waktu_selesai->format('H:i') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="border border-gray-200 px-4 py-3 text-gray-800">
                                                <span
                                                    class="inline-block px-3 py-1 bg-purple-100 text-purple-800 rounded text-sm font-medium">
                                                    {{ $jadwal->ruang }}
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
                        Guru ini belum memiliki jadwal mengajar
                    </div>
                @endif

                <!-- Metadata -->
                <div class="border-t border-gray-200 pt-6 text-xs text-gray-500">
                    <p>
                        <i class="fas fa-calendar-plus mr-2"></i>
                        Dibuat pada: {{ $guru->created_at->translatedFormat('d F Y H:i') }}
                    </p>
                    <p>
                        <i class="fas fa-calendar-check mr-2"></i>
                        Diperbarui pada: {{ $guru->updated_at->translatedFormat('d F Y H:i') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
