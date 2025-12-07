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
                        <!-- Weekly Schedule Grid -->
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @php
                                    $hariColors = [
                                        'Senin' => [
                                            'bg' => 'bg-blue-50',
                                            'border' => 'border-blue-400',
                                            'text' => 'text-blue-700',
                                            'badge' => 'bg-blue-500',
                                        ],
                                        'Selasa' => [
                                            'bg' => 'bg-green-50',
                                            'border' => 'border-green-400',
                                            'text' => 'text-green-700',
                                            'badge' => 'bg-green-500',
                                        ],
                                        'Rabu' => [
                                            'bg' => 'bg-yellow-50',
                                            'border' => 'border-yellow-400',
                                            'text' => 'text-yellow-700',
                                            'badge' => 'bg-yellow-500',
                                        ],
                                        'Kamis' => [
                                            'bg' => 'bg-orange-50',
                                            'border' => 'border-orange-400',
                                            'text' => 'text-orange-700',
                                            'badge' => 'bg-orange-500',
                                        ],
                                        'Jumat' => [
                                            'bg' => 'bg-red-50',
                                            'border' => 'border-red-400',
                                            'text' => 'text-red-700',
                                            'badge' => 'bg-red-500',
                                        ],
                                        'Sabtu' => [
                                            'bg' => 'bg-purple-50',
                                            'border' => 'border-purple-400',
                                            'text' => 'text-purple-700',
                                            'badge' => 'bg-purple-500',
                                        ],
                                    ];
                                @endphp

                                @foreach ($hariList as $hari)
                                    <div
                                        class="border-2 {{ $hariColors[$hari]['border'] }} rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200">
                                        <!-- Day Header -->
                                        <div class="{{ $hariColors[$hari]['badge'] }} text-white px-4 py-3">
                                            <div class="flex items-center justify-between">
                                                <h3 class="text-lg font-bold">{{ $hari }}</h3>
                                                @if (isset($jadwalByKelas[$siswa->kelas][$hari]))
                                                    <span
                                                        class="{{ $hariColors[$hari]['badge'] }} bg-opacity-30 px-2 py-1 rounded-full text-xs font-semibold">
                                                        {{ $jadwalByKelas[$siswa->kelas][$hari]->count() }} Pelajaran
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Schedule List -->
                                        <div class="{{ $hariColors[$hari]['bg'] }} p-4">
                                            @if (isset($jadwalByKelas[$siswa->kelas][$hari]))
                                                <div class="space-y-3">
                                                    @foreach ($jadwalByKelas[$siswa->kelas][$hari] as $jadwal)
                                                        <div
                                                            class="bg-white rounded-lg p-3 shadow-sm border border-gray-200 hover:border-{{ explode('-', $hariColors[$hari]['badge'])[1] }}-400 transition-colors">
                                                            <!-- Time Badge -->
                                                            <div class="flex items-center gap-2 mb-2">
                                                                <i
                                                                    class="fas fa-clock {{ $hariColors[$hari]['text'] }} text-sm"></i>
                                                                <span
                                                                    class="text-sm font-semibold {{ $hariColors[$hari]['text'] }}">
                                                                    {{ $jadwal->waktu_mulai?->format('H:i') }} -
                                                                    {{ $jadwal->waktu_selesai?->format('H:i') }}
                                                                </span>
                                                            </div>

                                                            <!-- Subject -->
                                                            <h4 class="font-bold text-gray-800 text-sm mb-1">
                                                                {{ $jadwal->mataPelajaran->nama_mapel }}
                                                            </h4>

                                                            <!-- Teacher & Room -->
                                                            <div
                                                                class="flex items-center justify-between text-xs text-gray-600">
                                                                <span class="flex items-center gap-1">
                                                                    <i class="fas fa-user-tie"></i>
                                                                    {{ $jadwal->guru->nama }}
                                                                </span>
                                                                <span class="px-2 py-1 bg-gray-100 rounded font-medium">
                                                                    <i class="fas fa-door-open"></i> {{ $jadwal->ruang }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="text-center py-8 text-gray-400">
                                                    <i class="fas fa-calendar-times text-3xl mb-2"></i>
                                                    <p class="text-sm">Tidak ada pelajaran</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
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
