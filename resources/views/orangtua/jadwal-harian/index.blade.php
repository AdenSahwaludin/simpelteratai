@extends('layouts.dashboard')

@section('title', 'Jadwal Anak')
@section('nav-color', 'bg-purple-600')
@section('sidebar-color', 'bg-purple-600')
@section('dashboard-title', 'Jadwal Anak')
@section('user-name', auth('orangtua')->user()->nama)
@section('user-role', 'Orang Tua')

@section('sidebar-menu')
    <x-sidebar-menu :guard="'orangtua'" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Jadwal Anak</h2>
            <p class="text-gray-600 mt-2">Lihat jadwal kegiatan harian anak Anda</p>
        </div>

        <!-- Filter Tanggal -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form method="GET" action="{{ route('orangtua.jadwal-harian.index') }}" class="flex gap-4 items-end">
                <div class="flex-1">
                    <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">Pilih Tanggal</label>
                    <select name="tanggal" id="tanggal" onchange="this.form.submit()"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        @if ($tanggalList->isEmpty())
                            <option value="">Belum ada jadwal</option>
                        @else
                            @foreach ($tanggalList as $tgl)
                                <option value="{{ $tgl->format('Y-m-d') }}"
                                    {{ $tanggal == $tgl->format('Y-m-d') ? 'selected' : '' }}>
                                    {{ $tgl->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </form>
        </div>

        @if ($jadwal->isEmpty())
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <i class="fas fa-calendar-times text-5xl text-gray-400 mb-4"></i>
                <p class="text-gray-500 text-lg">Tidak ada jadwal untuk tanggal yang dipilih.</p>
            </div>
        @else
            <!-- Jadwal Card - Sesuai Gambar -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-purple-600 to-purple-700 text-white p-6">
                    <div class="flex justify-between items-center">
                        <h3 class="text-2xl font-bold">Jadwal Anak</h3>
                        <span class="text-lg font-semibold">2024/2025</span>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <!-- Tanggal & Tema -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="border border-gray-300 rounded-lg p-4">
                            <span class="text-lg font-medium text-gray-800">
                                {{ \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                            </span>
                        </div>
                        @if ($tema)
                            <div class="border border-gray-300 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Tema</span>
                                    <span class="text-lg font-medium text-gray-800">{{ $tema }}</span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Tabel Jadwal -->
                    <div class="border border-gray-300 rounded-lg overflow-hidden mb-6">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th
                                        class="px-6 py-3 text-left text-sm font-semibold text-gray-700 border-b border-r border-gray-300">
                                        Tema
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-sm font-semibold text-gray-700 border-b border-gray-300">
                                        @if ($tema)
                                            {{ $tema }}
                                        @else
                                            Kegiatan
                                        @endif
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                @foreach ($jadwal as $item)
                                    <tr class="hover:bg-gray-50">
                                        <td
                                            class="px-6 py-4 border-b border-r border-gray-300 text-sm font-medium text-gray-700">
                                            {{ $item->waktu_mulai->format('H.i') }} -
                                            {{ $item->waktu_selesai->format('H:i') }}
                                        </td>
                                        <td class="px-6 py-4 border-b border-gray-300 text-sm text-gray-700">
                                            {{ $item->kegiatan }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Catatan -->
                    @if ($catatan)
                        <div class="border border-gray-300 rounded-lg p-6">
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">Catatan:</h4>
                            <p class="text-sm text-gray-700 leading-relaxed">{{ $catatan }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection
