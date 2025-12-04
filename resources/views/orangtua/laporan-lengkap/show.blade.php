@extends('layouts.dashboard')

@section('title', 'Detail Laporan Lengkap')
@section('dashboard-title', 'Detail Laporan Lengkap')
@section('nav-color', 'bg-purple-600')
@section('sidebar-color', 'bg-purple-600')
@section('user-name', auth('orangtua')->user()->nama)
@section('user-role', 'Orang Tua')

@section('sidebar-menu')
    <x-sidebar-menu guard="orangtua" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Detail Laporan Lengkap</h1>
                <p class="text-gray-600 text-sm mt-1">Laporan perkembangan komprehensif</p>
            </div>
            <a href="{{ route('orangtua.laporan-lengkap.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- Info Siswa -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Nama Siswa</label>
                    <p class="text-lg font-semibold text-gray-800">{{ $laporan->siswa->nama }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Kelas</label>
                    <p class="text-lg font-semibold text-gray-800">{{ $laporan->siswa->kelas }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Guru Pelapor</label>
                    <p class="text-lg font-semibold text-gray-800">{{ $laporan->guru->nama }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Periode Laporan</label>
                    <p class="text-lg font-semibold text-gray-800">
                        {{ \Carbon\Carbon::parse($laporan->periode_mulai)->format('d M Y') }} -
                        {{ \Carbon\Carbon::parse($laporan->periode_selesai)->format('d M Y') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Catatan Guru -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center gap-2">
                <i class="fas fa-clipboard text-purple-600"></i>
                Catatan Guru
            </h3>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-gray-700 whitespace-pre-line">{{ $laporan->catatan_guru }}</p>
            </div>
        </div>

        @if ($laporan->target_pembelajaran)
            <!-- Target Pembelajaran -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-bullseye text-blue-600"></i>
                    Target Pembelajaran
                </h3>
                <div class="bg-blue-50 rounded-lg p-4">
                    <p class="text-gray-700 whitespace-pre-line">{{ $laporan->target_pembelajaran }}</p>
                </div>
            </div>
        @endif

        @if ($laporan->pencapaian)
            <!-- Pencapaian -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-trophy text-green-600"></i>
                    Pencapaian
                </h3>
                <div class="bg-green-50 rounded-lg p-4">
                    <p class="text-gray-700 whitespace-pre-line">{{ $laporan->pencapaian }}</p>
                </div>
            </div>
        @endif

        @if ($laporan->saran)
            <!-- Saran -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-lightbulb text-yellow-600"></i>
                    Saran untuk Orang Tua
                </h3>
                <div class="bg-yellow-50 rounded-lg p-4">
                    <p class="text-gray-700 whitespace-pre-line">{{ $laporan->saran }}</p>
                </div>
            </div>
        @endif

        <!-- Data Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Kehadiran -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-calendar-check text-purple-600"></i>
                        Data Kehadiran
                    </h3>
                </div>
                <div class="p-6">
                    @if ($kehadiran->isEmpty())
                        <p class="text-gray-500 text-center py-4">Tidak ada data kehadiran pada periode ini.</p>
                    @else
                        <div class="space-y-3">
                            @foreach ($kehadiran as $item)
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        @if ($item->status_kehadiran === 'hadir')
                                            <span class="w-3 h-3 bg-green-500 rounded-full mr-3"></span>
                                            <span class="text-gray-700 font-medium">Hadir</span>
                                        @elseif($item->status_kehadiran === 'izin')
                                            <span class="w-3 h-3 bg-blue-500 rounded-full mr-3"></span>
                                            <span class="text-gray-700 font-medium">Izin</span>
                                        @elseif($item->status_kehadiran === 'sakit')
                                            <span class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></span>
                                            <span class="text-gray-700 font-medium">Sakit</span>
                                        @else
                                            <span class="w-3 h-3 bg-red-500 rounded-full mr-3"></span>
                                            <span class="text-gray-700 font-medium">Alpa</span>
                                        @endif
                                    </div>
                                    <span class="text-lg font-bold text-gray-900">{{ $item->total }} kali</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Perilaku -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-star text-yellow-600"></i>
                        Data Perilaku
                    </h3>
                </div>
                <div class="p-6">
                    @if ($perilaku->isEmpty())
                        <p class="text-gray-500 text-center py-4">Tidak ada catatan perilaku pada periode ini.</p>
                    @else
                        <div class="space-y-3 max-h-96 overflow-y-auto">
                            @foreach ($perilaku as $item)
                                <div class="p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="flex gap-1 flex-wrap">
                                            <span class="px-2 py-1 text-xs font-semibold rounded bg-blue-100 text-blue-800">
                                                Sosial: {{ $item->sosial }}
                                            </span>
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-800">
                                                Emosional: {{ $item->emosional }}
                                            </span>
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded bg-purple-100 text-purple-800">
                                                Disiplin: {{ $item->disiplin }}
                                            </span>
                                        </div>
                                        <span class="text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-700 mb-1">{{ $item->catatan_perilaku ?? '-' }}</p>
                                    <p class="text-xs text-gray-500">Oleh: {{ $item->guru->nama }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Data Nilai -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-book text-purple-600"></i>
                    Data Nilai
                </h3>
            </div>
            <div class="p-6">
                @if ($nilai->isEmpty())
                    <p class="text-gray-500 text-center py-4">Tidak ada data nilai pada periode ini.</p>
                @else
                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mata
                                        Pelajaran</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nilai</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catatan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($nilai as $item)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $item->mataPelajaran->nama_mapel }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-3 py-1 text-sm font-semibold rounded
                                                @if ($item->nilai >= 85) bg-green-100 text-green-800
                                                @elseif($item->nilai >= 70) bg-blue-100 text-blue-800
                                                @elseif($item->nilai >= 60) bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ $item->nilai }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $item->catatan ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Cards -->
                    <div class="md:hidden space-y-3">
                        @foreach ($nilai as $item)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-semibold text-gray-900">{{ $item->mataPelajaran->nama_mapel }}</h4>
                                    <span
                                        class="px-3 py-1 text-sm font-semibold rounded
                                        @if ($item->nilai >= 85) bg-green-100 text-green-800
                                        @elseif($item->nilai >= 70) bg-blue-100 text-blue-800
                                        @elseif($item->nilai >= 60) bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ $item->nilai }}
                                    </span>
                                </div>
                                @if ($item->catatan)
                                    <p class="text-sm text-gray-700 mb-2">{{ $item->catatan }}</p>
                                @endif
                                <p class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
