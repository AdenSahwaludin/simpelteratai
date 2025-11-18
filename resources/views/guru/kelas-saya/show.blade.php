@extends('layouts.dashboard')

@section('title', 'Detail Kelas ' . $ruang)
@section('nav-color', 'bg-green-600')
@section('sidebar-color', 'bg-green-600')
@section('dashboard-title', 'Detail Kelas')
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
                <h2 class="text-2xl font-bold text-gray-800">Detail Kelas {{ $ruang }}</h2>
                <p class="text-gray-600 mt-2">Daftar siswa dan jadwal mengajar di kelas ini</p>
            </div>
            <a href="{{ route('guru.kelas-saya.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-300">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <!-- Jadwal Mengajar Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-calendar-alt text-green-600 mr-2"></i>
                Jadwal Mengajar di Kelas {{ $ruang }}
            </h3>

            @if ($jadwal->isEmpty())
                <p class="text-gray-500 text-center py-4">Tidak ada jadwal mengajar di kelas ini.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Hari</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Waktu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Mata Pelajaran</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($jadwal as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-gray-900">{{ $item->hari }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-600">{{ $item->waktu }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full">
                                            {{ $item->mataPelajaran->nama_mapel }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Daftar Siswa Section -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-users text-green-600 mr-2"></i>
                Daftar Siswa Kelas {{ $ruang }} ({{ $siswa->total() }} siswa)
            </h3>

            @if ($siswa->isEmpty())
                <p class="text-gray-500 text-center py-4">Belum ada siswa di kelas ini.</p>
            @else
                <!-- Desktop Table View -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID Siswa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Orang Tua</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No. Telpon Ortu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($siswa as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-gray-900">{{ $item->id_siswa }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $item->nama }}</div>
                                            <div class="text-xs text-gray-500">{{ $item->kelas }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-600">{{ $item->orangTua->nama ?? '-' }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-600">{{ $item->orangTua->no_telpon ?? '-' }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('guru.siswa.show', $item->id_siswa) }}"
                                            class="text-green-600 hover:text-green-900">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden space-y-4">
                    @foreach ($siswa as $item)
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $item->nama }}</p>
                                    <p class="text-sm text-gray-600">{{ $item->id_siswa }} • {{ $item->kelas }}</p>
                                </div>
                            </div>
                            <div class="space-y-1 text-sm">
                                <p class="text-gray-600"><span class="font-medium">Orang Tua:</span>
                                    {{ $item->orangTua->nama ?? '-' }}</p>
                                <p class="text-gray-600"><span class="font-medium">No. Telpon:</span>
                                    {{ $item->orangTua->no_telpon ?? '-' }}</p>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('guru.siswa.show', $item->id_siswa) }}"
                                    class="inline-block bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm transition-colors duration-300">
                                    <i class="fas fa-eye mr-1"></i>Lihat Detail
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $siswa->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
