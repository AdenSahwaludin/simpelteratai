@extends('layouts.dashboard')

@section('title', 'Detail Laporan Lengkap')
@section('nav-color', 'bg-green-600')
@section('sidebar-color', 'bg-green-600')
@section('dashboard-title', 'Detail Laporan Lengkap')
@section('user-name', auth('guru')->user()->nama)
@section('user-role', 'Guru')

@section('content')
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
            <h2 class="text-2xl font-bold text-gray-800">Detail Laporan Lengkap</h2>
            <div class="flex gap-2">
                @if (!$laporan->dikirim_ke_ortu)
                    <form action="{{ route('guru.laporan-lengkap.kirim', $laporan->id_laporan_lengkap) }}" method="POST"
                        onsubmit="return confirm('Kirim laporan ini ke orang tua?')">
                        @csrf
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition font-medium">
                            <i class="fas fa-paper-plane mr-2"></i>Kirim ke Orang Tua
                        </button>
                    </form>
                @endif
                <a href="{{ route('guru.laporan-lengkap.edit', $laporan->id_laporan_lengkap) }}"
                    class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition font-medium">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <a href="{{ route('guru.laporan-lengkap.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Info Siswa -->
        <div class="bg-white rounded-lg shadow p-6 mb-4">
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
                    <label class="block text-sm font-medium text-gray-500 mb-1">Periode Laporan</label>
                    <p class="text-lg font-semibold text-gray-800">
                        {{ \Carbon\Carbon::parse($laporan->periode_mulai)->format('d M Y') }} -
                        {{ \Carbon\Carbon::parse($laporan->periode_selesai)->format('d M Y') }}
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                    @if ($laporan->dikirim_ke_ortu)
                        <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                            Terkirim -
                            {{ $laporan->tanggal_kirim ? \Carbon\Carbon::parse($laporan->tanggal_kirim)->format('d M Y H:i') : '' }}
                        </span>
                    @else
                        <span class="inline-block px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-semibold">
                            Draft
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Catatan Guru -->
        <div class="bg-white rounded-lg shadow p-6 mb-4">
            <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center gap-2">
                <i class="fas fa-clipboard text-green-600"></i>
                Catatan Guru
            </h3>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-gray-700 whitespace-pre-line">{{ $laporan->catatan_guru }}</p>
            </div>
        </div>

        @if ($laporan->target_pembelajaran)
            <div class="bg-white rounded-lg shadow p-6 mb-4">
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
            <div class="bg-white rounded-lg shadow p-6 mb-4">
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
            <div class="bg-white rounded-lg shadow p-6 mb-4">
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
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
            <!-- Kehadiran -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-calendar-check text-green-600"></i>
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
            <div class="bg-white rounded-lg shadow">
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
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-book text-green-600"></i>
                    Data Nilai
                </h3>
            </div>
            <div class="p-6">
                @if ($nilai->isEmpty())
                    <p class="text-gray-500 text-center py-4">Tidak ada data nilai pada periode ini.</p>
                @else
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

        <!-- Komentar Section -->
        <div class="bg-white rounded-lg shadow p-6 mb-4">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-comments text-purple-600"></i>
                Komentar & Diskusi
            </h3>

            <!-- New Comment Form -->
            <form action="{{ route('guru.laporan-lengkap.komentar.store', $laporan->id_laporan_lengkap) }}"
                method="POST" class="mb-6">
                @csrf
                <div class="mb-2">
                    <textarea name="komentar" rows="3" required maxlength="1000"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="Tulis komentar atau tanggapan..."></textarea>
                    <p class="text-xs text-gray-500 mt-1">Maksimal 1000 karakter</p>
                </div>
                @error('komentar')
                    <p class="text-red-600 text-sm mb-2">{{ $message }}</p>
                @enderror
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition font-medium">
                    <i class="fas fa-paper-plane mr-2"></i>Kirim Komentar
                </button>
            </form>

            <!-- Comments List -->
            <div class="border-t border-gray-200 pt-6">
                <x-komentar-list :komentar="$laporan->komentarList" :laporanId="$laporan->id_laporan_lengkap" userRole="guru" />
            </div>
        </div>
    </div>
@endsection
@section('sidebar-menu')
    <x-sidebar-menu :guard="'guru'" :currentRoute="request()->route()->getName()" />
@endsection
