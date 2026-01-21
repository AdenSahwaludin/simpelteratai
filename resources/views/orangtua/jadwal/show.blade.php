@extends('layouts.dashboard')

@section('title', 'Jadwal ' . $siswa->nama)
@section('dashboard-title', 'Jadwal Pelajaran')
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
            <a href="{{ route('orangtua.jadwal.index') }}"
                class="inline-flex items-center text-purple-600 hover:text-purple-700 mb-4 font-medium">
                <i class="fas fa-arrow-left mr-2"></i>
                <span>Kembali</span>
            </a>
            <div class="flex items-center gap-4">
                <div class="bg-purple-600 p-4 rounded-xl shadow-lg">
                    <i class="fas fa-calendar-alt text-white text-3xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Jadwal {{ $siswa->nama }}</h1>
                    <p class="text-gray-600 text-sm font-medium">Kelas {{ $siswa->kelas?->id_kelas ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        @if ($jadwal->count() > 0)
            <!-- Weekly Schedule Grid -->
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
                        class="border-2 {{ $hariColors[$hari]['border'] }} rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300">
                        <!-- Day Header -->
                        <div class="{{ $hariColors[$hari]['badge'] }} text-white px-5 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-calendar-day text-xl"></i>
                                    <h3 class="text-xl font-bold">{{ $hari }}</h3>
                                </div>
                                @if (isset($jadwal[$hari]))
                                    <span
                                        class="bg-white {{ $hariColors[$hari]['text'] }}  bg-opacity-30 px-3 py-1 rounded-full text-xs font-bold">
                                        {{ $jadwal[$hari]->count() }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Schedule List -->
                        <div class="{{ $hariColors[$hari]['bg'] }} p-4 min-h-[300px]">
                            @if (isset($jadwal[$hari]))
                                <div class="space-y-3">
                                    @foreach ($jadwal[$hari] as $item)
                                        <div
                                            class="bg-white rounded-lg p-4 shadow-sm border border-gray-200 hover:shadow-md hover:border-{{ explode('-', $hariColors[$hari]['badge'])[1] }}-400 transition-all duration-200">
                                            <!-- Time Badge -->
                                            <div class="flex items-center gap-2 mb-3">
                                                <div
                                                    class="{{ $hariColors[$hari]['badge'] }} text-white px-3 py-1.5 rounded-md text-sm font-bold flex items-center gap-2">
                                                    <i class="fas fa-clock text-xs"></i>
                                                    <span>{{ $item->waktu_mulai?->format('H:i') }} -
                                                        {{ $item->waktu_selesai?->format('H:i') }}</span>
                                                </div>
                                            </div>

                                            <!-- Subject -->
                                            <div class="mb-3">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <i class="fas fa-book {{ $hariColors[$hari]['text'] }} text-sm"></i>
                                                    <span class="text-xs text-gray-500 font-medium">Mata Pelajaran</span>
                                                </div>
                                                <h4 class="font-bold text-gray-900">{{ $item->mataPelajaran->nama_mapel }}
                                                </h4>
                                            </div>

                                            <!-- Teacher -->
                                            <div class="mb-3">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <i class="fas fa-chalkboard-teacher text-green-600 text-sm"></i>
                                                    <span class="text-xs text-gray-500 font-medium">Guru</span>
                                                </div>
                                                <p class="text-sm text-gray-700 font-semibold">{{ $item->guru->nama }}</p>
                                            </div>

                                            <!-- Room -->
                                            <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                                                <span class="text-xs text-gray-500 font-medium flex items-center gap-1">
                                                    <i class="fas fa-door-open"></i>
                                                    Ruang
                                                </span>
                                                <span
                                                    class="px-3 py-1 bg-gray-100 text-gray-800 rounded-md text-xs font-bold">
                                                    {{ $item->ruang }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="flex flex-col items-center justify-center h-[250px] text-gray-400">
                                    <i class="fas fa-calendar-times text-4xl mb-3"></i>
                                    <p class="text-sm font-medium">Tidak ada pelajaran</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md p-12 text-center text-gray-500">
                <i class="fas fa-calendar-times text-6xl mb-4 text-gray-300"></i>
                <p class="text-lg font-medium">Belum Ada Jadwal</p>
                <p class="text-sm mt-2">Jadwal pelajaran untuk {{ $siswa->nama }} (Kelas
                    {{ $siswa->kelas?->id_kelas ?? ' ' }}) belum
                    tersedia</p>
            </div>
        @endif
    </div>
@endsection
