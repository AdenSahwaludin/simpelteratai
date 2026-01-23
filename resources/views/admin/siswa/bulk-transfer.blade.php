@extends('layouts.dashboard')

@section('title', 'Pindah Kelas Secara Massal')
@section('nav-color', 'bg-blue-600')
@section('sidebar-color', 'bg-blue-600')
@section('dashboard-title', 'Pindah Kelas Secara Massal')
@section('user-name', auth('admin')->user()->nama)
@section('user-role', 'Admin')

@section('sidebar-menu')
    <x-sidebar-menu :guard="'admin'" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Pindah Kelas Secara Massal</h2>
                    <p class="text-gray-600 mt-2">Pindahkan beberapa siswa ke kelas lain secara bersamaan</p>
                </div>
                <a href="{{ route('admin.siswa.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Step 1: Select Source Class -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-filter mr-2 text-blue-600"></i>Langkah 1: Pilih Kelas Asal
            </h3>
            <form action="{{ route('admin.siswa.bulk-transfer') }}" method="GET">
                <div class="flex gap-3">
                    <div class="flex-1">
                        <select name="source_kelas" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">-- Pilih Kelas Asal --</option>
                            @foreach ($kelasList as $kelas)
                                <option value="{{ $kelas->id_kelas }}"
                                    {{ $sourceKelasId == $kelas->id_kelas ? 'selected' : '' }}>
                                    {{ $kelas->id_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition font-medium">
                        <i class="fas fa-search mr-2"></i>Tampilkan Siswa
                    </button>
                </div>
            </form>
        </div>

        @if ($sourceKelasId && $siswaList->isNotEmpty())
            <!-- Step 2: Select Students and Target Class -->
            <form action="{{ route('admin.siswa.bulk-transfer.process') }}" method="POST" id="bulkTransferForm">
                @csrf

                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-users mr-2 text-blue-600"></i>Langkah 2: Pilih Siswa dari Kelas
                        {{ $siswaList->first()->kelas->id_kelas }}
                    </h3>

                    <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" id="selectAll" class="mr-2 w-4 h-4">
                            <span class="font-medium text-blue-800">
                                Pilih Semua Siswa ({{ $siswaList->count() }} siswa)
                            </span>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-96 overflow-y-auto p-2">
                        @foreach ($siswaList as $siswa)
                            <label
                                class="flex items-start p-3 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer transition">
                                <input type="checkbox" name="siswa_ids[]" value="{{ $siswa->id_siswa }}"
                                    class="siswa-checkbox mr-3 mt-1 w-4 h-4">
                                <div class="flex-1">
                                    <div class="font-semibold text-gray-800">{{ $siswa->nama }}</div>
                                    <div class="text-sm text-gray-600">{{ $siswa->id_siswa }}</div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-user-circle mr-1"></i>{{ $siswa->orangTua->nama ?? 'N/A' }}
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <div class="mt-4 text-sm text-gray-600">
                        <i class="fas fa-info-circle mr-1"></i>
                        <span id="selectedCount">0</span> siswa dipilih
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-exchange-alt mr-2 text-blue-600"></i>Langkah 3: Pilih Kelas Tujuan
                    </h3>

                    <div class="flex gap-4 items-end">
                        <div class="flex-1">
                            <label for="target_kelas_nama" class="block text-sm font-medium text-gray-700 mb-2">
                                Kelas Tujuan <span class="text-red-500">*</span>
                            </label>
                            <select name="target_kelas_nama" id="target_kelas_nama" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">-- Pilih Kelas Tujuan --</option>
                                @foreach ($kelasList as $kelas)
                                    <option value="{{ $kelas->id_kelas }}">{{ $kelas->id_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="button" id="btnTambahKelas"
                            class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition font-medium">
                            <i class="fas fa-plus mr-2"></i>Tambah Kelas
                        </button>
                        <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition font-medium"
                            onclick="return confirmTransfer()">
                            <i class="fas fa-check-circle mr-2"></i>Proses Perpindahan
                        </button>
                    </div>
                </div>
            </form>
        @elseif($sourceKelasId && $siswaList->isEmpty())
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                <i class="fas fa-exclamation-circle text-5xl text-yellow-400 mb-3"></i>
                <p class="text-yellow-800 font-medium">Tidak ada siswa di kelas ini</p>
                <p class="text-yellow-600 text-sm mt-2">Pilih kelas lain yang memiliki siswa</p>
            </div>
        @else
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-8 text-center">
                <i class="fas fa-arrow-up text-5xl text-gray-400 mb-3"></i>
                <p class="text-gray-600 font-medium">Pilih kelas asal untuk menampilkan daftar siswa</p>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                initializeForm();
            });

            function initializeForm() {
                setupCheckboxes();
                setupModalHandlers();
            }

            function setupCheckboxes() {
                const selectAll = document.getElementById('selectAll');
                if (selectAll) {
                    selectAll.addEventListener('change', function() {
                        const checkboxes = document.querySelectorAll('.siswa-checkbox');
                        checkboxes.forEach(checkbox => {
                            checkbox.checked = this.checked;
                        });
                        updateSelectedCount();
                    });
                }

                document.querySelectorAll('.siswa-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', updateSelectedCount);
                });

                updateSelectedCount();
            }

            function updateSelectedCount() {
                const checked = document.querySelectorAll('.siswa-checkbox:checked').length;
                const selectedCountEl = document.getElementById('selectedCount');
                if (selectedCountEl) {
                    selectedCountEl.textContent = checked;
                }

                const selectAll = document.getElementById('selectAll');
                const allCheckboxes = document.querySelectorAll('.siswa-checkbox');
                if (selectAll && allCheckboxes.length > 0) {
                    selectAll.checked = checked === allCheckboxes.length;
                    selectAll.indeterminate = checked > 0 && checked < allCheckboxes.length;
                }
            }

            function confirmTransfer() {
                const checkedCount = document.querySelectorAll('.siswa-checkbox:checked').length;
                const targetKelas = document.getElementById('target_kelas_nama').value;

                if (checkedCount === 0) {
                    alert('Pilih minimal 1 siswa untuk dipindahkan!');
                    return false;
                }

                if (!targetKelas) {
                    alert('Masukkan kelas tujuan!');
                    return false;
                }

                return confirm(
                    `Apakah Anda yakin ingin memindahkan ${checkedCount} siswa ke kelas ${targetKelas}?\n\nProses ini tidak dapat dibatalkan.`
                );
            }

            function setupModalHandlers() {
                const btnTambahKelas = document.getElementById('btnTambahKelas');
                const modalTambahKelas = document.getElementById('modalTambahKelas');
                const btnCloseModal = document.getElementById('btnCloseModal');
                const btnBatalModal = document.getElementById('btnBatalModal');
                const formTambahKelas = document.getElementById('formTambahKelas');

                console.log('Setting up modal handlers...');

                if (btnTambahKelas) {
                    btnTambahKelas.addEventListener('click', function(e) {
                        e.preventDefault();
                        console.log('Opening modal');
                        modalTambahKelas?.classList.remove('hidden');
                    });
                }

                if (btnCloseModal) {
                    btnCloseModal.addEventListener('click', function(e) {
                        e.preventDefault();
                        modalTambahKelas?.classList.add('hidden');
                    });
                }

                if (btnBatalModal) {
                    btnBatalModal.addEventListener('click', function(e) {
                        e.preventDefault();
                        modalTambahKelas?.classList.add('hidden');
                    });
                }

                if (formTambahKelas) {
                    formTambahKelas.addEventListener('submit', handleFormSubmit);
                } else {
                    console.error('Form not found!');
                }
            }

            async function handleFormSubmit(e) {
                e.preventDefault();
                console.log('Form submitted');

                const idKelas = document.getElementById('newIdKelas').value.trim();
                const guruWali = document.getElementById('newGuruWali').value;
                const btnSubmit = this.querySelector('button[type="submit"]');
                const modalTambahKelas = document.getElementById('modalTambahKelas');

                if (!idKelas || !guruWali) {
                    alert('Isi semua field!');
                    return false;
                }

                console.log('Submitting with:', {
                    idKelas,
                    guruWali
                });

                btnSubmit.disabled = true;
                const originalHTML = btnSubmit.innerHTML;
                btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';

                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                        '{{ csrf_token() }}';

                    const response = await fetch('{{ route('admin.kelas.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            id_kelas: idKelas,
                            id_guru_wali: guruWali
                        })
                    });

                    console.log('Response status:', response.status);

                    const data = await response.json();
                    console.log('Response data:', data);

                    if (response.ok && data.success) {
                        // Add to dropdown
                        const select = document.getElementById('target_kelas_nama');
                        const option = document.createElement('option');
                        option.value = data.kelas.id_kelas;
                        option.textContent = data.kelas.id_kelas;
                        option.selected = true;
                        select.appendChild(option);

                        // Close modal and reset form
                        modalTambahKelas.classList.add('hidden');
                        this.reset();

                        // Show success message
                        showNotification('Kelas <strong>' + idKelas + '</strong> berhasil ditambahkan!', 'success');
                    } else {
                        throw new Error(data.message || 'Gagal menyimpan kelas');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showNotification('Error: ' + error.message, 'error');
                } finally {
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = originalHTML;
                }

                return false;
            }

            function showNotification(message, type) {
                const div = document.createElement('div');
                const bgClass = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' :
                    'bg-red-100 border-red-400 text-red-700';
                const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';

                div.className = `fixed top-4 right-4 ${bgClass} border px-4 py-3 rounded z-50 shadow-lg`;
                div.innerHTML = `<i class="fas ${icon} mr-2"></i>${message}`;
                document.body.appendChild(div);

                if (type === 'success') {
                    setTimeout(() => div.remove(), 3000);
                } else {
                    setTimeout(() => div.remove(), 5000);
                }
            }
        </script>

        <!-- Modal Tambah Kelas -->
        <div id="modalTambahKelas" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-800">Tambah Kelas Baru</h3>
                        <button id="btnCloseModal" type="button" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-times text-2xl"></i>
                        </button>
                    </div>

                    <form id="formTambahKelas" class="space-y-4">
                        <div>
                            <label for="newIdKelas" class="block text-sm font-medium text-gray-700 mb-2">
                                ID Kelas <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="newIdKelas" name="id_kelas" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                placeholder="Contoh: A, B, C, 5A, 6B, dll">
                        </div>

                        <div>
                            <label for="newGuruWali" class="block text-sm font-medium text-gray-700 mb-2">
                                Wali Kelas <span class="text-red-500">*</span>
                            </label>
                            <select id="newGuruWali" name="id_guru_wali" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                <option value="">-- Pilih Guru --</option>
                                @foreach ($guruAvailable as $guru)
                                    <option value="{{ $guru->id_guru }}">{{ $guru->nama }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-2 italic">
                                <i class="fas fa-info-circle mr-1"></i>Setiap guru hanya dapat menjadi wali kelas untuk 1
                                (satu) kelas
                            </p>
                        </div>

                        <div class="flex gap-3 pt-4 border-t border-gray-200">
                            <button type="button" id="btnBatalModal"
                                class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium border border-gray-300">
                                <i class="fas fa-times mr-2"></i>Batal
                            </button>
                            <button type="submit"
                                class="flex-1 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition font-medium">
                                <i class="fas fa-save mr-2"></i>Simpan Kelas
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endpush
@endsection
