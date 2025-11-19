@extends('layouts.dashboard')

@section('title', 'Tambah Absensi Per Kelas')
@section('nav-color', 'bg-green-600')
@section('sidebar-color', 'bg-green-600')
@section('dashboard-title', 'Tambah Absensi Per Kelas')
@section('user-name', auth('guru')->user()->nama)
@section('user-role', 'Guru')

@section('sidebar-menu')
    <x-sidebar-menu :guard="'guru'" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Tambah Absensi Per Kelas</h2>
                <p class="text-gray-600 mt-2">Input absensi untuk seluruh siswa dalam satu kelas sekaligus</p>
            </div>
            <a href="{{ route('guru.kelola-absensi.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-300">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('guru.kelola-absensi.store') }}" method="POST" id="absensiForm">
                @csrf

                <!-- Form Header -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    <div>
                        <label for="kelas" class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih Kelas <span class="text-red-500">*</span>
                        </label>
                        <select name="kelas" id="kelas" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('kelas') border-red-500 @enderror">
                            <option value="">-- Pilih Kelas --</option>
                            <option value="5A" {{ old('kelas') == '5A' ? 'selected' : '' }}>5A</option>
                            <option value="5B" {{ old('kelas') == '5B' ? 'selected' : '' }}>5B</option>
                            <option value="6A" {{ old('kelas') == '6A' ? 'selected' : '' }}>6A</option>
                        </select>
                        @error('kelas')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="id_jadwal" class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih Jadwal <span class="text-red-500">*</span>
                        </label>
                        <select name="id_jadwal" id="id_jadwal" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('id_jadwal') border-red-500 @enderror">
                            <option value="">-- Pilih Jadwal --</option>
                            @foreach ($jadwalList as $jadwal)
                                <option value="{{ $jadwal->id_jadwal }}"
                                    {{ old('id_jadwal') == $jadwal->id_jadwal ? 'selected' : '' }}>
                                    {{ $jadwal->mataPelajaran->nama_mapel }} ({{ $jadwal->ruang }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_jadwal')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal" id="tanggal" required
                            value="{{ old('tanggal', date('Y-m-d')) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('tanggal') border-red-500 @enderror">
                        @error('tanggal')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Student List -->
                <div id="siswaContainer" class="mb-8">
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-info-circle text-2xl mb-2"></i>
                        <p>Pilih kelas untuk menampilkan daftar siswa</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4 justify-end border-t border-gray-200 pt-6">
                    <a href="{{ route('guru.kelola-absensi.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition-colors duration-300">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit" id="submitBtn" disabled
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition-colors duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-save mr-2"></i>Simpan Absensi Kelas
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const kelasSelect = document.getElementById('kelas');
        const siswaContainer = document.getElementById('siswaContainer');
        const submitBtn = document.getElementById('submitBtn');

        kelasSelect.addEventListener('change', async function() {
            const kelas = this.value;

            if (!kelas) {
                siswaContainer.innerHTML = `
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-info-circle text-2xl mb-2"></i>
                        <p>Pilih kelas untuk menampilkan daftar siswa</p>
                    </div>
                `;
                submitBtn.disabled = true;
                return;
            }

            try {
                const response = await fetch(
                    `{{ route('guru.kelola-absensi.load-siswa') }}?kelas=${encodeURIComponent(kelas)}`);
                const data = await response.json();

                if (data.siswa.length === 0) {
                    siswaContainer.innerHTML = `
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-inbox text-2xl mb-2"></i>
                            <p>Tidak ada siswa dalam kelas ini</p>
                        </div>
                    `;
                    submitBtn.disabled = true;
                    return;
                }

                let html = `
                    <div class="mb-4">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">
                            <i class="fas fa-users mr-2 text-green-600"></i>
                            Daftar Siswa Kelas ${kelas} (${data.siswa.length} siswa)
                        </h3>
                        <div class="mb-4 flex gap-2">
                            <button type="button" onclick="setAllStatus('hadir')" class="bg-green-100 text-green-700 hover:bg-green-200 px-3 py-2 rounded text-sm font-medium transition">
                                <i class="fas fa-check mr-1"></i>Hadir Semua
                            </button>
                            <button type="button" onclick="setAllStatus('izin')" class="bg-blue-100 text-blue-700 hover:bg-blue-200 px-3 py-2 rounded text-sm font-medium transition">
                                <i class="fas fa-file mr-1"></i>Izin Semua
                            </button>
                            <button type="button" onclick="setAllStatus('sakit')" class="bg-yellow-100 text-yellow-700 hover:bg-yellow-200 px-3 py-2 rounded text-sm font-medium transition">
                                <i class="fas fa-heartbeat mr-1"></i>Sakit Semua
                            </button>
                            <button type="button" onclick="setAllStatus('alpha')" class="bg-red-100 text-red-700 hover:bg-red-200 px-3 py-2 rounded text-sm font-medium transition">
                                <i class="fas fa-times mr-1"></i>Alpha Semua
                            </button>
                        </div>
                    </div>
                    <div class="space-y-3">
                `;

                data.siswa.forEach(siswa => {
                    html += `
                        <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg border border-gray-200 hover:bg-gray-100 transition">
                            <div>
                                <p class="font-medium text-gray-900">${siswa.nama}</p>
                                <p class="text-sm text-gray-600">${siswa.kelas}</p>
                            </div>
                            <select name="absensi[${siswa.id_siswa}]" 
                                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                onchange="updateSelectColor(this)">
                                <option value="">-- Pilih Status --</option>
                                <option value="hadir">Hadir</option>
                                <option value="izin">Izin</option>
                                <option value="sakit">Sakit</option>
                                <option value="alpha">Alpha</option>
                            </select>
                        </div>
                    `;
                });

                html += '</div>';
                siswaContainer.innerHTML = html;
                submitBtn.disabled = false;
            } catch (error) {
                console.error('Error loading students:', error);
                siswaContainer.innerHTML = `
                    <div class="text-center py-8 text-red-500">
                        <i class="fas fa-exclamation-circle text-2xl mb-2"></i>
                        <p>Gagal memuat daftar siswa</p>
                    </div>
                `;
                submitBtn.disabled = true;
            }
        });

        function setAllStatus(status) {
            const selects = document.querySelectorAll('select[name^="absensi["]');
            selects.forEach(select => {
                select.value = status;
                updateSelectColor(select);
            });
        }

        function updateSelectColor(selectElement) {
            const statusColors = {
                'hadir': 'border-green-500 bg-green-50 text-green-700',
                'izin': 'border-blue-500 bg-blue-50 text-blue-700',
                'sakit': 'border-yellow-500 bg-yellow-50 text-yellow-700',
                'alpha': 'border-red-500 bg-red-50 text-red-700',
            };

            // Remove all color classes
            Object.values(statusColors).forEach(classes => {
                classes.split(' ').forEach(cls => selectElement.classList.remove(cls));
            });

            // Add appropriate color
            if (selectElement.value && statusColors[selectElement.value]) {
                statusColors[selectElement.value].split(' ').forEach(cls => selectElement.classList.add(cls));
            }
        }
    </script>
@endsection
