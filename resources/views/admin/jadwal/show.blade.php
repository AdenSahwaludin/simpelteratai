@extends('layouts.dashboard')

@section('title', 'Detail Jadwal')
@section('nav-color', 'bg-blue-600')
@section('sidebar-color', 'bg-blue-600')
@section('dashboard-title', 'Detail Jadwal')
@section('user-name', auth('admin')->user()->nama)
@section('user-role', 'Admin')
@section('sidebar-menu')
    <x-sidebar-menu :guard="'admin'" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Detail Jadwal</h2>
                <p class="text-gray-600 mt-2">Informasi lengkap jadwal mengajar</p>
            </div>
            <a href="{{ route('admin.jadwal.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-300">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Jadwal Info -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Jadwal</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">ID Jadwal</p>
                            <p class="font-semibold text-gray-900">{{ $jadwal->id_jadwal }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Guru</p>
                            <p class="font-semibold text-gray-900">{{ $jadwal->guru->nama }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Mata Pelajaran</p>
                            <p class="font-semibold text-gray-900">{{ $jadwal->mataPelajaran->nama_mapel }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Ruang</p>
                            <p class="font-semibold text-gray-900">{{ $jadwal->ruang }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Hari</p>
                            <p class="font-semibold text-blue-600 text-lg">{{ $jadwal->hari ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Waktu</p>
                            <p class="font-semibold text-gray-900">
                                @if ($jadwal->waktu_mulai && $jadwal->waktu_selesai)
                                    {{ $jadwal->waktu_mulai->format('H:i') }} - {{ $jadwal->waktu_selesai->format('H:i') }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Jumlah Siswa</p>
                            <p class="font-semibold text-blue-600">{{ $jadwal->siswa->count() }} siswa</p>
                        </div>
                    </div>

                    <div class="mt-6 flex gap-2 border-t pt-4">
                        <a href="{{ route('admin.jadwal.edit', $jadwal->id_jadwal) }}"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition">
                            <i class="fas fa-edit mr-2"></i>Edit Jadwal
                        </a>
                        <a href="{{ route('admin.jadwal-siswa.edit', $jadwal->id_jadwal) }}"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                            <i class="fas fa-users mr-2"></i>Kelola Siswa
                        </a>
                        <form action="{{ route('admin.jadwal.destroy', $jadwal->id_jadwal) }}" method="POST"
                            style="display: inline;"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">
                                <i class="fas fa-trash mr-2"></i>Hapus
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Daftar Siswa -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Daftar Siswa ({{ $jadwal->siswa->count() }})</h3>

                    @if ($jadwal->siswa->isEmpty())
                        <p class="text-gray-500 text-center py-8">Belum ada siswa yang terdaftar pada jadwal ini</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            ID</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nama</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Kelas</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Jenis Kelamin</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($jadwal->siswa as $siswa)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $siswa->id_siswa }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $siswa->nama }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $siswa->kelas?->id_kelas ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Attendance Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan Kehadiran</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-600">Total Siswa</p>
                            <p class="text-3xl font-bold text-blue-600">{{ $jadwal->siswa->count() }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Pertemuan</p>
                            <p class="text-3xl font-bold text-green-600">14</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Catatan Kehadiran</p>
                            <p class="text-3xl font-bold text-purple-600">
                                {{ \App\Models\Absensi::whereHas('pertemuan', fn($q) => $q->where('id_jadwal', $jadwal->id_jadwal))->count() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
