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
                            @foreach ($kelasList as $k)
                                <option value="{{ $k }}" {{ $sourceKelas == $k ? 'selected' : '' }}>
                                    Kelas {{ $k }}
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

        @if ($sourceKelas && $siswaList->isNotEmpty())
            <!-- Step 2: Select Students and Target Class -->
            <form action="{{ route('admin.siswa.bulk-transfer.process') }}" method="POST" id="bulkTransferForm">
                @csrf

                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-users mr-2 text-blue-600"></i>Langkah 2: Pilih Siswa dari Kelas
                        {{ $sourceKelas }}
                    </h3>

                    <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" id="selectAll" class="mr-2 w-4 h-4">
                            <span class="font-medium text-blue-800">
                                <i class="fas fa-check-square mr-1"></i>Pilih Semua Siswa ({{ $siswaList->count() }} siswa)
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
                            <label for="target_kelas" class="block text-sm font-medium text-gray-700 mb-2">
                                Kelas Tujuan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="target_kelas" id="target_kelas" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Contoh: 5A, 6B, atau kelas lainnya">
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-lightbulb mr-1"></i>Anda dapat memasukkan nama kelas yang sudah ada atau
                                membuat kelas baru
                            </p>
                        </div>
                        <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition font-medium"
                            onclick="return confirmTransfer()">
                            <i class="fas fa-check-circle mr-2"></i>Proses Perpindahan Kelas
                        </button>
                    </div>
                </div>
            </form>
        @elseif($sourceKelas && $siswaList->isEmpty())
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                <i class="fas fa-exclamation-circle text-5xl text-yellow-400 mb-3"></i>
                <p class="text-yellow-800 font-medium">Tidak ada siswa di kelas {{ $sourceKelas }}</p>
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
            // Select all checkbox functionality
            document.getElementById('selectAll')?.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.siswa-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateSelectedCount();
            });

            // Update selected count
            document.querySelectorAll('.siswa-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectedCount);
            });

            function updateSelectedCount() {
                const checked = document.querySelectorAll('.siswa-checkbox:checked').length;
                document.getElementById('selectedCount').textContent = checked;

                // Update "select all" checkbox state
                const selectAll = document.getElementById('selectAll');
                const allCheckboxes = document.querySelectorAll('.siswa-checkbox');
                if (selectAll && allCheckboxes.length > 0) {
                    selectAll.checked = checked === allCheckboxes.length;
                    selectAll.indeterminate = checked > 0 && checked < allCheckboxes.length;
                }
            }

            function confirmTransfer() {
                const checkedCount = document.querySelectorAll('.siswa-checkbox:checked').length;
                const targetKelas = document.getElementById('target_kelas').value;

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

            // Initial count update
            updateSelectedCount();
        </script>
    @endpush
@endsection
