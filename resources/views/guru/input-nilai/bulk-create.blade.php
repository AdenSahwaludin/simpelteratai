@extends('layouts.dashboard')

@section('title', 'Input Nilai Massal')
@section('nav-color', 'bg-green-600')
@section('sidebar-color', 'bg-green-600')
@section('dashboard-title', 'Input Nilai Massal')
@section('user-name', auth('guru')->user()->nama)
@section('user-role', 'Guru')

@section('sidebar-menu')
    <x-sidebar-menu :guard="'guru'" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Page Header -->
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Input Nilai Massal</h2>
                <p class="text-gray-600 mt-2">Input nilai untuk seluruh siswa dalam satu jadwal sekaligus</p>
            </div>
            <a href="{{ route('guru.input-nilai.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-300">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg mb-6">
                <div class="flex">
                    <div class="flex shrink-0">
                        <i class="fas fa-check-circle text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg mb-6">
                <div class="flex">
                    <div class="flex shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Selection Form -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Pilih Jadwal dan Pertemuan</h3>

            <div class="mb-6">
                <label for="jadwalSelect" class="block text-sm font-medium text-gray-700 mb-2">
                    Jadwal Pelajaran <span class="text-red-500">*</span>
                </label>
                <select id="jadwalSelect"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">-- Pilih Jadwal --</option>
                    @foreach ($jadwalList as $jadwal)
                        <option value="{{ $jadwal->id_jadwal }}">
                            {{ $jadwal->mataPelajaran->nama_mapel }} ({{ $jadwal->ruang }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div id="absensiSelectContainer" class="mb-6 hidden">
                <label for="absensiSelect" class="block text-sm font-medium text-gray-700 mb-2">
                    Pertemuan / Absensi <span class="text-red-500">*</span>
                </label>
                <select id="absensiSelect"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">-- Pilih Pertemuan --</option>
                </select>
                <p class="text-sm text-gray-500 mt-1">Pilih pertemuan untuk input nilai siswa</p>
            </div>

            <div id="siswaContainer" class="hidden">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">
                        Daftar Siswa <span id="siswaCount" class="text-green-600">(0 siswa)</span>
                    </h3>
                    <div class="flex gap-2">
                        <button type="button" id="btnFillAll" onclick="fillAllNilai()"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                            <i class="fas fa-fill"></i> Isi Semua
                        </button>
                        <button type="button" id="btnClearAll" onclick="clearAllNilai()"
                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                            <i class="fas fa-eraser"></i> Hapus Semua
                        </button>
                    </div>
                </div>

                <!-- Loading Indicator -->
                <div id="loadingIndicator" class="hidden text-center py-4">
                    <div class="inline-block">
                        <i class="fas fa-spinner fa-spin text-green-600 text-2xl"></i>
                        <p class="text-gray-600 mt-2">Memuat data siswa...</p>
                    </div>
                </div>

                <!-- Error Message -->
                <div id="errorMessage" class="hidden bg-red-50 border-l-4 border-red-400 p-4 rounded-lg mb-4">
                    <div class="flex">
                        <div class="flex shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700" id="errorText"></p>
                        </div>
                    </div>
                </div>

                <!-- Form Section -->
                <form id="nilaiForm" action="{{ route('guru.input-nilai.bulk-store') }}" method="POST" class="hidden">
                    @csrf

                    <!-- Hidden Jadwal Input -->
                    <input type="hidden" name="id_jadwal" id="hiddenJadwal">

                    <!-- Desktop Table View -->
                    <div class="hidden md:block overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr class="border-b border-gray-200">
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">
                                        No.
                                    </th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">
                                        Nama Siswa
                                    </th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">
                                        Kelas
                                    </th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">
                                        Nilai (0-100)
                                    </th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">
                                        Komentar
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="tableBody" class="divide-y divide-gray-200">
                                <!-- Populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card View -->
                    <div id="cardView" class="md:hidden space-y-4">
                        <!-- Populated by JavaScript -->
                    </div>

                    <!-- Form Actions -->
                    <div class="flex gap-4 justify-end mt-6">
                        <a href="{{ route('guru.input-nilai.index') }}"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition-colors duration-300">
                            <i class="fas fa-times mr-2"></i>Batal
                        </a>
                        <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition-colors duration-300">
                            <i class="fas fa-save mr-2"></i>Simpan Semua Nilai
                        </button>
                    </div>
                </form>
            </div>

            <!-- Empty State -->
            <div id="emptyState" class="text-center py-8 text-gray-500">
                <i class="fas fa-inbox text-4xl mb-4 text-gray-400"></i>
                <p>Pilih jadwal untuk menampilkan daftar siswa</p>
            </div>
        </div>
    </div>

    <style>
        .nilai-input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 1rem;
        }

        .nilai-input:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        .nilai-input.invalid {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .komentar-input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            resize: vertical;
        }

        .komentar-input:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }
    </style>

    <script>
        const jadwalSelect = document.getElementById('jadwalSelect');
        const absensiSelectContainer = document.getElementById('absensiSelectContainer');
        const absensiSelect = document.getElementById('absensiSelect');
        const siswaContainer = document.getElementById('siswaContainer');
        const emptyState = document.getElementById('emptyState');
        const loadingIndicator = document.getElementById('loadingIndicator');
        const errorMessage = document.getElementById('errorMessage');
        const errorText = document.getElementById('errorText');
        const nilaiForm = document.getElementById('nilaiForm');
        const tableBody = document.getElementById('tableBody');
        const cardView = document.getElementById('cardView');
        const siswaCount = document.getElementById('siswaCount');
        const hiddenJadwal = document.getElementById('hiddenJadwal');

        let currentPertemuanList = [];
        let currentSiswaList = [];

        jadwalSelect.addEventListener('change', loadJadwalData);
        absensiSelect.addEventListener('change', loadSiswa);

        async function loadJadwalData() {
            const id_jadwal = jadwalSelect.value;

            if (!id_jadwal) {
                siswaContainer.classList.add('hidden');
                absensiSelectContainer.classList.add('hidden');
                emptyState.classList.remove('hidden');
                nilaiForm.classList.add('hidden');
                return;
            }

            emptyState.classList.add('hidden');
            loadingIndicator.classList.remove('hidden');
            errorMessage.classList.add('hidden');
            siswaContainer.classList.remove('hidden');
            absensiSelectContainer.classList.add('hidden');
            nilaiForm.classList.add('hidden');

            try {
                const response = await fetch(
                    `/guru/kelola-nilai-load-siswa?id_jadwal=${id_jadwal}`
                );

                if (!response.ok) {
                    throw new Error('Gagal memuat data jadwal');
                }

                const data = await response.json();
                loadingIndicator.classList.add('hidden');

                if (data.siswa.length === 0) {
                    siswaContainer.classList.add('hidden');
                    errorMessage.classList.remove('hidden');
                    errorText.textContent = 'Tidak ada siswa yang terdaftar untuk jadwal ini.';
                    return;
                }

                if (data.pertemuan.length === 0) {
                    siswaContainer.classList.add('hidden');
                    errorMessage.classList.remove('hidden');
                    errorText.textContent = 'Tidak ada pertemuan yang tersedia untuk jadwal ini.';
                    return;
                }

                // Store data
                currentSiswaList = data.siswa;
                currentPertemuanList = data.pertemuan;

                // Set hidden jadwal value
                hiddenJadwal.value = id_jadwal;

                // Populate absensi select
                absensiSelect.innerHTML = '<option value="">-- Pilih Pertemuan --</option>';
                data.pertemuan.forEach(p => {
                    const option = document.createElement('option');
                    option.value = p.id_pertemuan; // Store id_pertemuan for AJAX call
                    option.textContent = `Pertemuan ${p.pertemuan_ke} - ${p.tanggal_formatted}`;
                    absensiSelect.appendChild(option);
                });

                absensiSelectContainer.classList.remove('hidden');
            } catch (error) {
                loadingIndicator.classList.add('hidden');
                errorMessage.classList.remove('hidden');
                errorText.textContent = error.message || 'Terjadi kesalahan saat memuat data jadwal.';
            }
        }

        async function loadSiswa() {
            const id_pertemuan = absensiSelect.value;

            if (!id_pertemuan) {
                nilaiForm.classList.add('hidden');
                return;
            }

            loadingIndicator.classList.remove('hidden');
            errorMessage.classList.add('hidden');

            try {
                // Get absensi for this pertemuan and siswa
                const response = await fetch(
                    `/guru/kelola-nilai-load-absensi?id_pertemuan=${id_pertemuan}&id_jadwal=${jadwalSelect.value}`
                );

                if (!response.ok) {
                    throw new Error('Gagal memuat data absensi');
                }

                const absensiData = await response.json();
                loadingIndicator.classList.add('hidden');

                // Render table
                tableBody.innerHTML = '';
                cardView.innerHTML = '';

                currentSiswaList.forEach((siswa, index) => {
                    const absensiForSiswa = absensiData.absensiList.find(a => a.id_siswa === siswa.id_siswa) ||
                    {};
                    const existingData = absensiForSiswa.nilai || {};
                    const nilai = existingData.nilai || '';
                    const komentar = existingData.komentar || '';
                    const id_absensi_siswa = absensiForSiswa.id_absensi || '';

                    // Table row (Desktop only)
                    const tr = document.createElement('tr');
                    tr.className = 'border-b border-gray-200 hover:bg-gray-50';
                    tr.innerHTML = `
                        <td class="px-6 py-4 text-sm text-gray-600">${index + 1}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">${siswa.nama}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">${siswa.id_kelas || 'N/A'}</td>
                        <td class="px-6 py-4">
                            <input type="hidden" name="id_absensi[${siswa.id_siswa}]" value="${id_absensi_siswa}" />
                            <input 
                                type="number" 
                                name="nilai[${siswa.id_siswa}]" 
                                class="nilai-input" 
                                min="0" 
                                max="100" 
                                value="${nilai}"
                                placeholder="0-100"
                                data-siswa="${siswa.id_siswa}"
                            />
                        </td>
                        <td class="px-6 py-4">
                            <textarea 
                                name="komentar[${siswa.id_siswa}]" 
                                class="komentar-input" 
                                rows="2" 
                                placeholder="Komentar..."
                                data-siswa="${siswa.id_siswa}"
                            >${komentar}</textarea>
                        </td>
                    `;
                    tableBody.appendChild(tr);

                    // Card for mobile
                    const card = document.createElement('div');
                    card.className = 'bg-gray-50 rounded-lg p-4 border border-gray-200';
                    card.innerHTML = `
                        <div class="mb-3">
                            <p class="font-semibold text-gray-800">${siswa.nama}</p>
                            <p class="text-sm text-gray-600">Kelas ${siswa.id_kelas || 'N/A'}</p>
                        </div>
                        <div class="space-y-3">
                            <input type="hidden" name="id_absensi[${siswa.id_siswa}]" value="${id_absensi_siswa}" />
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nilai</label>
                                <input 
                                    type="number" 
                                    name="nilai[${siswa.id_siswa}]" 
                                    class="nilai-input nilai-input-mobile" 
                                    min="0" 
                                    max="100" 
                                    value="${nilai}"
                                    placeholder="0-100"
                                    data-siswa="${siswa.id_siswa}"
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Komentar</label>
                                <textarea 
                                    name="komentar[${siswa.id_siswa}]" 
                                    class="komentar-input komentar-input-mobile" 
                                    rows="3" 
                                    placeholder="Komentar..."
                                    data-siswa="${siswa.id_siswa}"
                                >${komentar}</textarea>
                            </div>
                        </div>
                    `;
                    cardView.appendChild(card);
                });

                siswaCount.textContent = `(${currentSiswaList.length} siswa)`;
                nilaiForm.classList.remove('hidden');
            } catch (error) {
                loadingIndicator.classList.add('hidden');
                errorMessage.classList.remove('hidden');
                errorText.textContent = error.message || 'Terjadi kesalahan saat memuat data siswa.';
                nilaiForm.classList.add('hidden');
            }
        }

        function fillAllNilai() {
            const nilai = prompt('Masukkan nilai untuk semua siswa:', '');
            if (nilai === null) return;

            if (isNaN(nilai) || nilai < 0 || nilai > 100) {
                alert('Nilai harus berupa angka antara 0-100');
                return;
            }

            document.querySelectorAll('.nilai-input').forEach(input => {
                input.value = nilai;
            });
        }

        function clearAllNilai() {
            if (!confirm('Apakah Anda yakin ingin menghapus semua nilai?')) {
                return;
            }

            document.querySelectorAll('.nilai-input').forEach(input => {
                input.value = '';
                input.classList.remove('invalid');
            });
        }

        // Form submission with validation
        nilaiForm.addEventListener('submit', function(e) {
            let hasError = false;

            // Disable inputs dari view yang hidden untuk avoid duplicate submission
            const isMobile = window.innerWidth < 768;

            if (isMobile) {
                // Disable desktop inputs
                document.querySelectorAll('#tableBody input, #tableBody textarea').forEach(input => {
                    input.disabled = true;
                });
            } else {
                // Disable mobile inputs
                document.querySelectorAll('.nilai-input-mobile, .komentar-input-mobile').forEach(input => {
                    input.disabled = true;
                });
            }

            // Validate only active inputs
            const activeInputs = isMobile ?
                document.querySelectorAll('.nilai-input-mobile') :
                document.querySelectorAll('#tableBody .nilai-input');

            activeInputs.forEach(input => {
                const value = input.value.trim();

                if (value === '') {
                    // Empty is allowed
                    input.classList.remove('invalid');
                } else if (isNaN(value) || value < 0 || value > 100) {
                    input.classList.add('invalid');
                    hasError = true;
                } else {
                    input.classList.remove('invalid');
                }
            });

            if (hasError) {
                e.preventDefault();
                alert('Terdapat nilai yang tidak valid. Silakan perbaiki nilai yang berwarna merah.');

                // Re-enable all inputs if validation fails
                document.querySelectorAll('input[disabled], textarea[disabled]').forEach(input => {
                    input.disabled = false;
                });

                return false;
            }
        });
    </script>
@endsection
