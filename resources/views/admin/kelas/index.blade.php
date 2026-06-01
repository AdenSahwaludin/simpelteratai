@extends('layouts.dashboard')

@section('title', 'Data Kelas')
@section('nav-color', 'bg-blue-600')
@section('sidebar-color', 'bg-blue-600')
@section('dashboard-title', 'Data Kelas')
@section('user-name', auth('admin')->user()->nama)
@section('user-role', 'Admin')

@section('sidebar-menu')
    <x-sidebar-menu :guard="'admin'" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i>
                <span>Kembali ke Dashboard</span>
            </a>
            <div class="flex gap-2">
                <button onclick="document.getElementById('modalTambahKelas').classList.remove('hidden')"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition font-medium text-center">
                    <i class="fas fa-plus mr-2"></i>Tambah Kelas
                </button>
                <a href="{{ route('admin.siswa.bulk-transfer') }}"
                    class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition font-medium text-center">
                    <i class="fas fa-exchange-alt mr-2"></i>Pindah Kelas Massal
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-school text-blue-600 mr-3"></i>
                    Data Kelas
                </h2>
                <p class="text-sm text-gray-600 mt-1">Kelola data kelas dan penugasan wali kelas</p>
            </div>

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-gray-200">
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">ID Kelas</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Wali Kelas</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Jumlah Siswa</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kelasList as $kelas)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">
                                        {{ $kelas->id_kelas }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($kelas->guruWali)
                                        <span class="text-gray-900">{{ $kelas->guruWali->nama }}</span>
                                    @else
                                        <span class="text-gray-400 italic">Belum ditugaskan</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-gray-700 font-medium">{{ $kelas->siswa()->where('status', 'Aktif')->count() }} siswa</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.kelas.edit', $kelas->id_kelas) }}"
                                            class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-700 hover:bg-green-200 rounded-lg transition"
                                            title="Kelola Wali Kelas">
                                            <i class="fas fa-user-tie mr-1"></i>
                                            <span class="text-xs">Wali Kelas</span>
                                        </a>
                                        <button onclick="openEditModal('{{ $kelas->id_kelas }}')"
                                            class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-lg transition"
                                            title="Edit Nama Kelas">
                                            <i class="fas fa-edit mr-1"></i>
                                            <span class="text-xs">Edit</span>
                                        </button>
                                        <form action="{{ route('admin.kelas.destroy', $kelas->id_kelas) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 hover:bg-red-200 rounded-lg transition"
                                                title="Hapus Kelas">
                                                <i class="fas fa-trash mr-1"></i>
                                                <span class="text-xs">Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-inbox text-3xl mb-2"></i>
                                    <p class="mt-2">Belum ada kelas</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Kelas -->
    <div id="modalTambahKelas" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Tambah Kelas Baru</h3>
                    <button type="button" onclick="document.getElementById('modalTambahKelas').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
                <form action="{{ route('admin.kelas.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="newIdKelas" class="block text-sm font-medium text-gray-700 mb-2">
                            ID Kelas / Nama Kelas <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="newIdKelas" name="id_kelas" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Contoh: 1A, 2B, dll">
                    </div>
                    <div>
                        <label for="newGuruWali" class="block text-sm font-medium text-gray-700 mb-2">
                            Wali Kelas
                        </label>
                        <select id="newGuruWali" name="id_guru_wali"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">-- Pilih Guru --</option>
                            @foreach ($guru as $g)
                                <option value="{{ $g->id_guru }}">{{ $g->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-3 pt-4 border-t border-gray-200">
                        <button type="button" onclick="document.getElementById('modalTambahKelas').classList.add('hidden')"
                            class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium border border-gray-300">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                            <i class="fas fa-save mr-2"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Kelas -->
    <div id="modalEditKelas" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Edit Nama Kelas</h3>
                    <button type="button" onclick="document.getElementById('modalEditKelas').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
                <form id="formEditKelas" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="editIdKelas" class="block text-sm font-medium text-gray-700 mb-2">
                            ID Kelas / Nama Kelas <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="editIdKelas" name="id_kelas_baru" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div class="flex gap-3 pt-4 border-t border-gray-200">
                        <button type="button" onclick="document.getElementById('modalEditKelas').classList.add('hidden')"
                            class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium border border-gray-300">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function openEditModal(idKelas) {
                const modal = document.getElementById('modalEditKelas');
                const form = document.getElementById('formEditKelas');
                const inputId = document.getElementById('editIdKelas');
                
                // Update action route
                form.action = `/admin/kelas/${idKelas}/nama`;
                inputId.value = idKelas;
                
                modal.classList.remove('hidden');
            }
        </script>
    @endpush
@endsection
