@extends('layouts.dashboard')

@section('title', 'Orang Tua Dashboard')
@section('nav-color', 'bg-purple-600')
@section('sidebar-color', 'bg-purple-600')
@section('dashboard-title', 'Dashboard Orang Tua')
@section('user-name', auth('orangtua')->user()->nama)
@section('user-role', 'Orang Tua')
@section('welcome-message', 'Selamat datang! Pantau perkembangan anak Anda dari dashboard ini.')

@section('sidebar-menu')
    <x-sidebar-menu :guard="'orangtua'" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Children -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-linear-to-br from-purple-500 to-purple-600 p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium">Jumlah Anak</p>
                            <h3 class="text-3xl font-bold mt-1">{{ $totalAnak }}</h3>
                        </div>
                        <div class="bg-white/20 p-4 rounded-full">
                            <i class="fas fa-child text-3xl"></i>
                        </div>
                    </div>
                </div>
                <div class="p-4 bg-gray-50">
                    <a href="{{ route('orangtua.anak.index') }}"
                        class="text-purple-600 hover:text-purple-700 text-sm font-medium flex items-center gap-2">
                        Lihat Detail
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>

            <!-- Average Score -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-linear-to-br from-green-500 to-green-600 p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Rata-rata Nilai</p>
                            <h3 class="text-3xl font-bold mt-1">{{ $rataRataNilai }}</h3>
                        </div>
                        <div class="bg-white/20 p-4 rounded-full">
                            <i class="fas fa-chart-line text-3xl"></i>
                        </div>
                    </div>
                </div>
                <div class="p-4 bg-gray-50">
                    <a href="{{ route('orangtua.perkembangan.index') }}"
                        class="text-green-600 hover:text-green-700 text-sm font-medium flex items-center gap-2">
                        Lihat Perkembangan
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>

            <!-- Attendance Percentage -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-linear-to-br from-blue-500 to-blue-600 p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Kehadiran</p>
                            <h3 class="text-3xl font-bold mt-1">{{ $persentaseKehadiran }}%</h3>
                        </div>
                        <div class="bg-white/20 p-4 rounded-full">
                            <i class="fas fa-calendar-check text-3xl"></i>
                        </div>
                    </div>
                </div>
                <div class="p-4 bg-gray-50">
                    <a href="{{ route('orangtua.kehadiran.index') }}"
                        class="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center gap-2">
                        Lihat Kehadiran
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>

            <!-- Behavior Notes -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-linear-to-br from-yellow-500 to-yellow-600 p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-yellow-100 text-sm font-medium">Catatan Perilaku</p>
                            <h3 class="text-3xl font-bold mt-1">{{ $totalPerilaku }}</h3>
                        </div>
                        <div class="bg-white/20 p-4 rounded-full">
                            <i class="fas fa-star text-3xl"></i>
                        </div>
                    </div>
                </div>
                <div class="p-4 bg-gray-50">
                    <a href="{{ route('orangtua.perilaku.index') }}"
                        class="text-yellow-600 hover:text-yellow-700 text-sm font-medium flex items-center gap-2">
                        Lihat Perilaku
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Attendance Breakdown -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-chart-pie text-purple-600"></i>
                Rincian Kehadiran
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-green-50 p-4 rounded-lg border-l-4 border-green-500">
                    <p class="text-gray-600 text-sm">Hadir</p>
                    <p class="text-2xl font-bold text-green-700">{{ $hadirCount }}</p>
                </div>
                <div class="bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-500">
                    <p class="text-gray-600 text-sm">Izin</p>
                    <p class="text-2xl font-bold text-yellow-700">{{ $izinCount }}</p>
                </div>
                <div class="bg-orange-50 p-4 rounded-lg border-l-4 border-orange-500">
                    <p class="text-gray-600 text-sm">Sakit</p>
                    <p class="text-2xl font-bold text-orange-700">{{ $sakitCount }}</p>
                </div>
                <div class="bg-red-50 p-4 rounded-lg border-l-4 border-red-500">
                    <p class="text-gray-600 text-sm">Alpha</p>
                    <p class="text-2xl font-bold text-red-700">{{ $alphaCount }}</p>
                </div>
            </div>
        </div>

        <!-- Children List -->
        @if ($anak->count() > 0)
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-users text-purple-600"></i>
                    Data Anak
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($anak as $child)
                        <div class="bg-purple-50 p-4 rounded-lg border border-purple-200 hover:shadow-md transition">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="bg-purple-100 p-3 rounded-full">
                                    <i class="fas fa-user-graduate text-purple-600 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800">{{ $child->nama_siswa }}</h3>
                                    <p class="text-sm text-gray-600">Kelas {{ $child->kelas?->id_kelas ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <a href="{{ route('orangtua.anak.show', $child->id_siswa) }}"
                                class="block w-full bg-purple-600 hover:bg-purple-700 text-white text-center py-2 rounded transition text-sm font-medium">
                                Lihat Detail
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Recent Development Reports -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-chart-line text-purple-600"></i>
                    Perkembangan Terbaru
                </h2>
                @if ($laporanTerbaru->count() > 0)
                    <div class="space-y-3">
                        @foreach ($laporanTerbaru as $laporan)
                            <div class="bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-800">{{ $laporan->siswa->nama_siswa }}</h4>
                                        <p class="text-sm text-gray-600">{{ $laporan->mataPelajaran->nama_mata_pelajaran }}
                                        </p>
                                        <div class="flex items-center gap-2 mt-2">
                                            @php
                                                $nilai = $laporan->nilai;
                                                if ($nilai >= 80) {
                                                    $badgeClass = 'bg-green-100 text-green-800';
                                                } elseif ($nilai >= 70) {
                                                    $badgeClass = 'bg-blue-100 text-blue-800';
                                                } elseif ($nilai >= 60) {
                                                    $badgeClass = 'bg-yellow-100 text-yellow-800';
                                                } else {
                                                    $badgeClass = 'bg-red-100 text-red-800';
                                                }
                                            @endphp
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                                                Nilai: {{ $nilai }}
                                            </span>
                                        </div>
                                    </div>
                                    <a href="{{ route('orangtua.perkembangan.show', $laporan->id_laporan) }}"
                                        class="text-purple-600 hover:text-purple-700">
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('orangtua.perkembangan.index') }}"
                            class="text-purple-600 hover:text-purple-700 text-sm font-medium flex items-center gap-2">
                            Lihat Semua
                            <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">Belum ada laporan perkembangan</p>
                @endif
            </div>

            <!-- Recent Behavior Notes -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-star text-purple-600"></i>
                    Catatan Perilaku Terbaru
                </h2>
                @if ($perilakuTerbaru->count() > 0)
                    <div class="space-y-3">
                        @foreach ($perilakuTerbaru as $perilaku)
                            <div class="bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-800">
                                            {{ $perilaku->siswa->nama ?? '-' }}
                                        </h4>
                                        <p class="text-sm text-gray-600 mt-1 line-clamp-2">
                                            {{ $perilaku->catatan_perilaku }}</p>
                                        <p class="text-xs text-gray-500 mt-2">
                                            <i class="fas fa-user"></i> {{ $perilaku->guru->nama ?? '-' }}
                                        </p>
                                    </div>
                                    <a href="{{ route('orangtua.perilaku.show', $perilaku->id_perilaku) }}"
                                        class="text-purple-600 hover:text-purple-700 ml-2">
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('orangtua.perilaku.index') }}"
                            class="text-purple-600 hover:text-purple-700 text-sm font-medium flex items-center gap-2">
                            Lihat Semua
                            <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">Belum ada catatan perilaku</p>
                @endif
            </div>
        </div>

        <!-- Recent Announcements -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-bullhorn text-purple-600"></i>
                Pengumuman Terbaru
            </h2>
            @if ($pengumumanTerbaru->count() > 0)
                <div class="space-y-4">
                    @foreach ($pengumumanTerbaru as $pengumuman)
                        <div class="bg-gray-50 p-4 rounded-lg border-l-4 border-red-500 hover:shadow-md transition">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded">
                                            {{ $pengumuman->tanggal->format('d M Y') }}
                                        </span>
                                    </div>
                                    <h4 class="font-semibold text-gray-800 mb-1">{{ $pengumuman->judul }}</h4>
                                    <p class="text-sm text-gray-600 line-clamp-2">{{ $pengumuman->isi }}</p>
                                </div>
                                <a href="{{ route('orangtua.pengumuman.show', $pengumuman->id_pengumuman) }}"
                                    class="text-purple-600 hover:text-purple-700 ml-4">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    <a href="{{ route('orangtua.pengumuman.index') }}"
                        class="text-purple-600 hover:text-purple-700 text-sm font-medium flex items-center gap-2">
                        Lihat Semua Pengumuman
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
            @else
                <p class="text-gray-500 text-center py-8">Belum ada pengumuman</p>
            @endif
        </div>
    </div>
@endsection
