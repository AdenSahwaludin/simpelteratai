@extends('layouts.dashboard')

@section('title', 'Detail Siswa')
@section('nav-color', 'bg-green-600')
@section('sidebar-color', 'bg-green-600')
@section('dashboard-title', 'Detail Siswa')
@section('user-name', auth('guru')->user()->nama)
@section('user-role', 'Guru')

@section('sidebar-menu')
    <x-sidebar-menu :guard="'guru'" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Page Header with Back Button -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Detail Siswa</h2>
                <p class="text-gray-600 mt-2">Informasi lengkap tentang siswa</p>
            </div>
            <a href="{{ route('guru.siswa.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-300">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <!-- Student Information -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Main Info Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="text-center mb-6">
                        <div class="bg-green-100 w-24 h-24 rounded-full mx-auto flex items-center justify-center mb-4">
                            <i class="fas fa-user text-4xl text-green-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">{{ $siswa->nama }}</h3>
                        <p class="text-gray-600 text-sm mt-1">{{ $siswa->id_siswa }}</p>
                        <span class="inline-block bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full mt-2">
                            {{ $siswa->kelas?->id_kelas ?? 'N/A' }}
                        </span>
                    </div>

                    <div class="border-t pt-4 space-y-3">
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Nama Orang Tua</p>
                            <p class="text-sm font-medium text-gray-800 mt-1">{{ $siswa->orangTua->nama ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Email Orang Tua</p>
                            <p class="text-sm font-medium text-gray-800 mt-1">{{ $siswa->orangTua->email ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase">No. Telpon</p>
                            <p class="text-sm font-medium text-gray-800 mt-1">{{ $siswa->orangTua->no_telpon ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="lg:col-span-2">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                    <div class="bg-white rounded-lg shadow-md p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm">Total Laporan</p>
                                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $siswa->laporanPerkembangan->count() }}
                                </p>
                            </div>
                            <div class="bg-blue-100 p-3 rounded-lg">
                                <i class="fas fa-file-alt text-blue-600 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm">Catatan Perilaku</p>
                                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $siswa->perilaku->count() }}</p>
                            </div>
                            <div class="bg-purple-100 p-3 rounded-lg">
                                <i class="fas fa-clipboard-list text-purple-600 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm">Total Absensi</p>
                                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $siswa->absensi->count() }}</p>
                            </div>
                            <div class="bg-orange-100 p-3 rounded-lg">
                                <i class="fas fa-calendar-check text-orange-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <a href="{{ route('guru.input-nilai.bulk') }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg text-center transition-colors duration-300">
                            <i class="fas fa-plus-circle mr-2"></i>Input Nilai
                        </a>
                        <a href="{{ route('guru.catatan-perilaku.create', ['id_siswa' => $siswa->id_siswa]) }}"
                            class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-3 rounded-lg text-center transition-colors duration-300">
                            <i class="fas fa-clipboard mr-2"></i>Tambah Catatan
                        </a>
                        <a href="{{ route('guru.kelola-absensi.create', ['id_siswa' => $siswa->id_siswa]) }}"
                            class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-3 rounded-lg text-center transition-colors duration-300">
                            <i class="fas fa-calendar-plus mr-2"></i>Input Absensi
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs for Detailed Information -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px">
                    <button onclick="showTab('laporan')" id="tab-laporan"
                        class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-green-600 text-green-600">
                        Laporan Perkembangan
                    </button>
                    <button onclick="showTab('perilaku')" id="tab-perilaku"
                        class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Catatan Perilaku
                    </button>
                    <button onclick="showTab('absensi')" id="tab-absensi"
                        class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Riwayat Absensi
                    </button>
                </nav>
            </div>

            <!-- Laporan Perkembangan Tab -->
            <div id="content-laporan" class="tab-content p-6">
                @if ($siswa->laporanPerkembangan->isEmpty())
                    <p class="text-gray-500 text-center py-8">Belum ada laporan perkembangan.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mata
                                        Pelajaran</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nilai</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Komentar
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($siswa->laporanPerkembangan as $laporan)
                                    <tr>
                                        <td class="px-6 py-4">{{ $laporan->mataPelajaran->nama_mapel }}</td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                                                {{ $laporan->nilai }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-gray-600">{{ $laporan->komentar ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Catatan Perilaku Tab -->
            <div id="content-perilaku" class="tab-content p-6 hidden">
                @if ($siswa->perilaku->isEmpty())
                    <p class="text-gray-500 text-center py-8">Belum ada catatan perilaku.</p>
                @else
                    <div class="space-y-4">
                        @foreach ($siswa->perilaku as $perilaku)
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="flex justify-between items-start mb-2">
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ \Carbon\Carbon::parse($perilaku->tanggal)->format('d M Y') }}
                                    </p>
                                    <p class="text-xs text-gray-500">oleh {{ $perilaku->guru->nama ?? 'Guru' }}</p>
                                </div>
                                <p class="text-gray-800">{{ $perilaku->catatan_perilaku }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Riwayat Absensi Tab -->
            <div id="content-absensi" class="tab-content p-6 hidden">
                @if ($siswa->absensi->isEmpty())
                    <p class="text-gray-500 text-center py-8">Belum ada riwayat absensi.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($siswa->absensi->sortByDesc('tanggal') as $absensi)
                                    <tr>
                                        <td class="px-6 py-4">
                                            {{ \Carbon\Carbon::parse($absensi->tanggal)->format('d M Y') }}</td>
                                        <td class="px-6 py-4">
                                            @php
                                                $statusColors = [
                                                    'hadir' => 'bg-green-100 text-green-800',
                                                    'izin' => 'bg-blue-100 text-blue-800',
                                                    'sakit' => 'bg-yellow-100 text-yellow-800',
                                                    'alpha' => 'bg-red-100 text-red-800',
                                                ];
                                            @endphp
                                            <span
                                                class="px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$absensi->status_kehadiran] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst($absensi->status_kehadiran) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-gray-600">{{ $absensi->keterangan ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });

            // Remove active styling from all tab buttons
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('border-green-600', 'text-green-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });

            // Show selected tab content
            document.getElementById('content-' + tabName).classList.remove('hidden');

            // Add active styling to selected tab button
            const activeButton = document.getElementById('tab-' + tabName);
            activeButton.classList.add('border-green-600', 'text-green-600');
            activeButton.classList.remove('border-transparent', 'text-gray-500');
        }
    </script>
@endsection
