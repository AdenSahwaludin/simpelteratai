@extends('layouts.dashboard')

@section('title', 'Detail Siswa')
@section('nav-color', 'bg-blue-600')
@section('sidebar-color', 'bg-blue-600')
@section('dashboard-title', 'Detail Siswa')
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

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Header dengan Info Dasar -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-8 text-white">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="inline-block px-3 py-1 bg-white text-blue-600 rounded-full font-bold text-lg">
                                {{ $siswa->id_siswa }}
                            </span>
                            <span class="inline-block px-3 py-1 bg-blue-900 text-white rounded-full text-sm font-semibold">
                                {{ $siswa->kelas }}
                            </span>
                        </div>
                        <h1 class="text-3xl font-bold">{{ $siswa->nama }}</h1>
                        <p class="text-blue-100 mt-1">
                            <i class="fas fa-book mr-2"></i>
                            {{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.siswa.edit', $siswa->id_siswa) }}"
                            class="inline-flex items-center px-4 py-2 bg-white text-blue-600 hover:bg-gray-100 rounded-lg transition font-medium">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </a>
                        <form action="{{ route('admin.siswa.destroy', $siswa->id_siswa) }}" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus siswa {{ $siswa->nama }}?')"
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
                <!-- Data Pribadi -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-user-circle text-blue-600 mr-3"></i>
                        Informasi Pribadi
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="border border-gray-200 rounded-lg p-4">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Tempat Lahir</label>
                            <p class="text-lg text-gray-800 font-medium mt-1">
                                {{ $siswa->tempat_lahir ?: '-' }}
                            </p>
                        </div>
                        <div class="border border-gray-200 rounded-lg p-4">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Tanggal Lahir</label>
                            <p class="text-lg text-gray-800 font-medium mt-1">
                                @if ($siswa->tanggal_lahir)
                                    {{ \Carbon\Carbon::parse($siswa->tanggal_lahir)->translatedFormat('d F Y') }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Data Kontak & Alamat -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-map-marker-alt text-blue-600 mr-3"></i>
                        Kontak & Alamat
                    </h2>
                    <div class="border border-gray-200 rounded-lg p-4 mb-4">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Alamat Lengkap</label>
                        <p class="text-gray-800 mt-2 leading-relaxed">{{ $siswa->alamat }}</p>
                    </div>
                </div>

                <!-- Data Orang Tua -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-users text-blue-600 mr-3"></i>
                        Data Orang Tua / Wali
                    </h2>
                    @if ($siswa->orangTua)
                        <div class="border-2 border-blue-200 bg-blue-50 rounded-lg p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Nama</label>
                                    <p class="text-lg text-gray-800 font-medium mt-1">{{ $siswa->orangTua->nama }}</p>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Email</label>
                                    <p class="text-gray-800 mt-1">
                                        @if ($siswa->orangTua->email)
                                            <a href="mailto:{{ $siswa->orangTua->email }}"
                                                class="text-blue-600 hover:underline">
                                                {{ $siswa->orangTua->email }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Nomor
                                        Telepon</label>
                                    <p class="text-gray-800 mt-1">
                                        @if ($siswa->orangTua->no_telpon)
                                            <a href="tel:{{ $siswa->orangTua->no_telpon }}"
                                                class="text-blue-600 hover:underline">
                                                {{ $siswa->orangTua->no_telpon }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="border border-yellow-200 bg-yellow-50 rounded-lg p-4 text-yellow-800">
                            <i class="fas fa-info-circle mr-2"></i>
                            Belum ada data orang tua yang terkait
                        </div>
                    @endif
                </div>

                <!-- Riwayat Akademik -->
                @if ($siswa->laporanPerkembangan->isNotEmpty())
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-chart-line text-blue-600 mr-3"></i>
                            Nilai Akademik Terakhir
                        </h2>
                        <div class="overflow-x-auto">
                            <table class="w-full border-collapse">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th
                                            class="border border-gray-200 px-4 py-2 text-left text-sm font-semibold text-gray-700">
                                            Mata Pelajaran</th>
                                        <th
                                            class="border border-gray-200 px-4 py-2 text-left text-sm font-semibold text-gray-700">
                                            Nilai</th>
                                        <th
                                            class="border border-gray-200 px-4 py-2 text-left text-sm font-semibold text-gray-700">
                                            Komentar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($siswa->laporanPerkembangan as $laporan)
                                        <tr class="hover:bg-gray-50">
                                            <td class="border border-gray-200 px-4 py-3 text-gray-800">
                                                {{ $laporan->mataPelajaran->nama_mapel }}
                                            </td>
                                            <td class="border border-gray-200 px-4 py-3">
                                                <span
                                                    class="inline-block px-3 py-1 rounded-full font-bold text-white
                                                    @if ($laporan->nilai >= 85) bg-green-500
                                                    @elseif ($laporan->nilai >= 70) bg-blue-500
                                                    @elseif ($laporan->nilai >= 60) bg-yellow-500
                                                    @else bg-red-500 @endif">
                                                    {{ $laporan->nilai }}
                                                </span>
                                            </td>
                                            <td class="border border-gray-200 px-4 py-3 text-gray-800">
                                                {{ $laporan->komentar ?: '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- Riwayat Absensi -->
                @if ($siswa->absensi->isNotEmpty())
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-calendar-check text-blue-600 mr-3"></i>
                            Riwayat Absensi Terbaru
                        </h2>
                        <div class="space-y-3">
                            @foreach ($siswa->absensi->take(5) as $absen)
                                <div class="border border-gray-200 rounded-lg p-4 flex items-center justify-between">
                                    <div>
                                        <p class="text-sm text-gray-600">{{ $absen->tanggal->translatedFormat('d F Y') }}
                                        </p>
                                        <p class="text-gray-800 font-medium">
                                            {{ $absen->jadwal->mataPelajaran->nama_mapel }}</p>
                                    </div>
                                    <span
                                        class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                                        @if ($absen->status_kehadiran == 'hadir') bg-green-100 text-green-800
                                        @elseif ($absen->status_kehadiran == 'izin') bg-yellow-100 text-yellow-800
                                        @elseif ($absen->status_kehadiran == 'sakit') bg-blue-100 text-blue-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($absen->status_kehadiran) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Catatan Perilaku -->
                @if ($siswa->perilaku->isNotEmpty())
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-star text-blue-600 mr-3"></i>
                            Catatan Perilaku Terbaru
                        </h2>
                        <div class="space-y-3">
                            @foreach ($siswa->perilaku->take(5) as $perilaku)
                                <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                    <p class="text-sm text-gray-600">{{ $perilaku->created_at->translatedFormat('d F Y') }}
                                    </p>
                                    <p class="text-gray-800 mt-2">{{ $perilaku->catatan_perilaku }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Metadata -->
                <div class="border-t border-gray-200 pt-6 text-sm text-gray-600">
                    <p><i class="fas fa-clock mr-2"></i>Dibuat: {{ $siswa->created_at->translatedFormat('d F Y H:i') }}</p>
                    <p><i class="fas fa-pencil-alt mr-2"></i>Terakhir diperbarui:
                        {{ $siswa->updated_at->translatedFormat('d F Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
