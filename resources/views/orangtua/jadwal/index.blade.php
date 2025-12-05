@extends('layouts.dashboard')

@section('title', 'Jadwal Anak')
@section('dashboard-title', 'Jadwal Anak')
@section('nav-color', 'bg-purple-600')
@section('sidebar-color', 'bg-purple-600')
@section('user-name', auth('orangtua')->user()->nama)
@section('user-role', 'Orang Tua')

@section('sidebar-menu')
    <x-sidebar-menu guard="orangtua" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Header Section -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Jadwal Pelajaran Anak</h1>
            <p class="text-gray-600 text-sm mt-1">Lihat jadwal pelajaran anak Anda</p>
        </div>

        @if ($siswaList->count() > 0)
            @foreach ($siswaList as $siswa)
                <div class="bg-white rounded-xl shadow-lg mb-6 overflow-hidden border border-gray-100">
                    <!-- Header Card -->
                    <div class="bg-purple-600 px-6 py-5">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-4">
                                <div class="bg-white p-3 rounded-xl shadow-sm">
                                    <i class="fas fa-calendar-alt text-purple-600 text-xl"></i>
                                </div>
                                <div class="text-white">
                                    <h2 class="text-xl font-bold">{{ $siswa->nama }}</h2>
                                    <p class="text-sm text-purple-100">Kelas {{ $siswa->kelas }}</p>
                                </div>
                            </div>
                            <a href="{{ route('orangtua.jadwal.show', $siswa->id_siswa) }}"
                                class="bg-white text-purple-600 hover:bg-purple-50 px-4 py-2 rounded-lg transition-all duration-200 flex items-center gap-2 font-medium">
                                <i class="fas fa-eye text-sm"></i>
                                <span class="text-sm">Detail</span>
                            </a>
                        </div>
                    </div>

                    <!-- Schedule Content -->
                    @if (isset($jadwalByKelas[$siswa->kelas]) && $jadwalByKelas[$siswa->kelas]->count() > 0)
                        <!-- Desktop View -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hari
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mata
                                            Pelajaran</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Guru
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ruang
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($hariList as $hari)
                                        @if (isset($jadwalByKelas[$siswa->kelas][$hari]))
                                            @foreach ($jadwalByKelas[$siswa->kelas][$hari] as $index => $jadwal)
                                                <tr class="hover:bg-gray-50">
                                                    @if ($index === 0)
                                                        <td class="px-6 py-4 font-semibold text-purple-600"
                                                            rowspan="{{ $jadwalByKelas[$siswa->kelas][$hari]->count() }}">
                                                            {{ $hari }}
                                                        </td>
                                                    @endif
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                        {{ $jadwal->waktu_mulai?->format('H:i') }} -
                                                        {{ $jadwal->waktu_selesai?->format('H:i') }}
                                                    </td>
                                                    <td class="px-6 py-4 text-sm">
                                                        <span
                                                            class="font-medium text-gray-800">{{ $jadwal->mataPelajaran->nama_mapel }}</span>
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-gray-600">
                                                        {{ $jadwal->guru->nama }}
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-gray-600">
                                                        <span
                                                            class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">{{ $jadwal->ruang }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile View -->
                        <div class="md:hidden p-4 space-y-4">
                            @foreach ($hariList as $hari)
                                @if (isset($jadwalByKelas[$siswa->kelas][$hari]))
                                    <div class="border-l-4 border-purple-500 pl-4">
                                        <h3 class="font-bold text-purple-600 mb-3">{{ $hari }}</h3>
                                        <div class="space-y-3">
                                            @foreach ($jadwalByKelas[$siswa->kelas][$hari] as $jadwal)
                                                <div class="bg-gray-50 rounded-lg p-4">
                                                    <div class="flex items-start gap-3">
                                                        <div
                                                            class="bg-purple-100 text-purple-600 px-3 py-1 rounded-lg text-sm font-medium whitespace-nowrap">
                                                            {{ $jadwal->waktu_mulai?->format('H:i') }} -
                                                            {{ $jadwal->waktu_selesai?->format('H:i') }}
                                                        </div>
                                                        <div class="flex-1">
                                                            <p class="font-semibold text-gray-800">
                                                                {{ $jadwal->mataPelajaran->nama_mapel }}</p>
                                                            <p class="text-sm text-gray-600 mt-1">{{ $jadwal->guru->nama }}
                                                            </p>
                                                            <span
                                                                class="inline-block mt-2 px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">{{ $jadwal->ruang }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="p-12 text-center text-gray-500">
                            <i class="fas fa-calendar-times text-6xl mb-4 text-gray-300"></i>
                            <p class="text-lg font-medium">Belum Ada Jadwal</p>
                            <p class="text-sm mt-2">Jadwal pelajaran untuk kelas {{ $siswa->kelas }} belum tersedia</p>
                        </div>
                    @endif
                </div>
            @endforeach
        @else
            <div class="bg-white rounded-lg shadow-md p-12 text-center text-gray-500">
                <i class="fas fa-child text-6xl mb-4 text-gray-300"></i>
                <p class="text-lg font-medium">Belum Ada Data Anak</p>
                <p class="text-sm mt-2">Hubungi admin untuk menambahkan data anak Anda</p>
            </div>
        @endif
    </div>
@endsection
