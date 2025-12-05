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
                    <p class="text-gray-600 text-sm font-medium">Kelas {{ $siswa->kelas }}</p>
                </div>
            </div>
        </div>

        @if ($jadwal->count() > 0)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
                <!-- Desktop View -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-purple-600">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Hari
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Waktu
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Mata
                                    Pelajaran</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Guru
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Ruang
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($hariList as $hari)
                                @if (isset($jadwal[$hari]))
                                    @foreach ($jadwal[$hari] as $index => $item)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            @if ($index === 0)
                                                <td class="px-6 py-4 font-bold text-purple-600 bg-purple-50"
                                                    rowspan="{{ $jadwal[$hari]->count() }}">
                                                    <div class="flex items-center gap-2">
                                                        <i class="fas fa-calendar-day text-sm"></i>
                                                        <span>{{ $hari }}</span>
                                                    </div>
                                                </td>
                                            @endif
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <div
                                                    class="flex items-center gap-2 bg-purple-600 text-white px-3 py-2 rounded-lg inline-block font-semibold">
                                                    <i class="fas fa-clock text-xs"></i>
                                                    <span>{{ $item->waktu_mulai?->format('H:i') }} -
                                                        {{ $item->waktu_selesai?->format('H:i') }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-2">
                                                    <i class="fas fa-book text-purple-600"></i>
                                                    <span
                                                        class="font-bold text-gray-900">{{ $item->mataPelajaran->nama_mapel }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-2 text-sm text-gray-700">
                                                    <i class="fas fa-chalkboard-teacher text-green-600"></i>
                                                    <span class="font-medium">{{ $item->guru->nama }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span
                                                    class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg text-xs font-semibold">
                                                    {{ $item->ruang }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile View -->
                <div class="md:hidden p-4">
                    @foreach ($hariList as $hari)
                        @if (isset($jadwal[$hari]))
                            <div class="mb-6">
                                <div class="bg-purple-600 text-white px-4 py-3 rounded-t-xl flex items-center gap-2">
                                    <i class="fas fa-calendar-day"></i>
                                    <h3 class="font-bold text-lg">{{ $hari }}</h3>
                                </div>
                                <div class="bg-gray-50 rounded-b-xl p-4 space-y-3">
                                    @foreach ($jadwal[$hari] as $item)
                                        <div
                                            class="bg-white rounded-xl p-4 shadow-md border border-gray-200 hover:shadow-lg transition-shadow">
                                            <div class="flex items-center gap-2 mb-3">
                                                <div
                                                    class="bg-purple-600 text-white px-3 py-2 rounded-lg text-sm font-bold">
                                                    <i class="fas fa-clock text-xs mr-1"></i>
                                                    {{ $item->waktu_mulai?->format('H:i') }} -
                                                    {{ $item->waktu_selesai?->format('H:i') }}
                                                </div>
                                            </div>
                                            <div class="space-y-3">
                                                <div class="flex items-start gap-3">
                                                    <i class="fas fa-book text-purple-600 mt-1 text-lg"></i>
                                                    <div>
                                                        <p class="text-xs text-gray-500 font-medium mb-1">Mata Pelajaran</p>
                                                        <p class="font-bold text-gray-900">
                                                            {{ $item->mataPelajaran->nama_mapel }}</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-start gap-3">
                                                    <i class="fas fa-chalkboard-teacher text-green-600 mt-1 text-lg"></i>
                                                    <div>
                                                        <p class="text-xs text-gray-500 font-medium mb-1">Guru</p>
                                                        <p class="text-sm text-gray-800 font-medium">
                                                            {{ $item->guru->nama }}</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-start gap-3">
                                                    <i class="fas fa-door-open text-gray-600 mt-1 text-lg"></i>
                                                    <div>
                                                        <p class="text-xs text-gray-500 font-medium mb-1">Ruang</p>
                                                        <span
                                                            class="inline-block px-3 py-1 bg-gray-100 text-gray-700 rounded-lg text-xs font-bold">
                                                            {{ $item->ruang }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md p-12 text-center text-gray-500">
                <i class="fas fa-calendar-times text-6xl mb-4 text-gray-300"></i>
                <p class="text-lg font-medium">Belum Ada Jadwal</p>
                <p class="text-sm mt-2">Jadwal pelajaran untuk {{ $siswa->nama }} (Kelas {{ $siswa->kelas }}) belum
                    tersedia</p>
            </div>
        @endif
    </div>
@endsection
