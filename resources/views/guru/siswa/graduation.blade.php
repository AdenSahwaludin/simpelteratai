@extends('layouts.dashboard')

@section('title', 'Proses Kelulusan')
@section('nav-color', 'bg-green-600')
@section('sidebar-color', 'bg-green-600')
@section('dashboard-title', 'Proses Kelulusan')
@section('user-name', auth('guru')->user()->nama)
@section('user-role', 'Guru')

@section('sidebar-menu')
    <x-sidebar-menu :guard="'guru'" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="mb-4">
            <a href="{{ route('guru.siswa.index') }}" class="inline-flex items-center text-green-600 hover:text-green-800 transition font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Data Siswa
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8">
            <div class="mb-8 border-b border-gray-100 pb-6">
                <h2 class="text-xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-graduation-cap text-green-600 mr-3 text-2xl"></i>
                    Proses Kelulusan (Ubah ke Alumni)
                </h2>
                <p class="text-gray-500 mt-2">Pilih kelas asal untuk menampilkan daftar siswa aktif, lalu pilih siswa yang akan diluluskan menjadi Alumni.</p>
            </div>

            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-3 text-lg"></i>
                        <p class="text-green-700 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-exclamation-circle text-red-500 mr-3 text-lg"></i>
                        <p class="text-red-700 font-bold">Terjadi Kesalahan</p>
                    </div>
                    <ul class="list-disc list-inside text-red-600 text-sm ml-7 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form Pilih Kelas Asal -->
            <form action="{{ route('guru.siswa.graduation') }}" method="GET" class="mb-8">
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <label for="source_kelas" class="block text-sm font-semibold text-gray-700 mb-2">Pilih Kelas Asal <span class="text-red-500">*</span></label>
                        <select name="source_kelas" id="source_kelas"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-100 focus:border-transparent transition shadow-sm"
                            onchange="this.form.submit()" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($kelasList as $kelas)
                                <option value="{{ $kelas->id_kelas }}"
                                    {{ $sourceKelasId == $kelas->id_kelas ? 'selected' : '' }}>
                                    Kelas {{ $kelas->id_kelas }} (Guru: {{ $kelas->guruWali->nama ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <a href="{{ route('guru.siswa.graduation') }}"
                            class="px-6 py-3 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg transition font-medium text-center shadow-sm w-full sm:w-auto">
                            <i class="fas fa-sync-alt mr-2"></i>Reset
                        </a>
                    </div>
                </div>
            </form>

            @if ($sourceKelasId)
                <!-- Form Proses Kelulusan -->
                <form action="{{ route('guru.siswa.process-graduation') }}" method="POST" id="formKelulusan">
                    @csrf
                    
                    <div class="mb-6 flex justify-between items-center bg-green-50 p-4 rounded-lg border border-green-500">
                        <div>
                            <h3 class="font-bold text-green-900">Daftar Siswa Aktif</h3>
                            <p class="text-sm text-green-700">Pilih siswa yang akan diproses kelulusannya</p>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-semibold text-green-800 bg-white px-3 py-1 rounded-full shadow-sm">
                                Total: {{ $siswaList->count() }} siswa
                            </span>
                        </div>
                    </div>

                    @if ($siswaList->isEmpty())
                        <div class="text-center py-10 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                            <i class="fas fa-inbox text-4xl text-gray-400 mb-3"></i>
                            <p class="text-gray-600 font-medium">Tidak ada siswa aktif di kelas ini.</p>
                        </div>
                    @else
                        <div class="border border-gray-200 rounded-lg overflow-hidden shadow-sm mb-8">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-4 text-left">
                                            <div class="flex items-center">
                                                <input type="checkbox" id="selectAll"
                                                    class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500 cursor-pointer transition">
                                                <label for="selectAll" class="ml-2 text-xs font-semibold text-gray-500 uppercase tracking-wider cursor-pointer">
                                                    Pilih Semua
                                                </label>
                                            </div>
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            NIS
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Nama Siswa
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            L/P
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($siswaList as $siswa)
                                        <tr class="hover:bg-green-50 transition cursor-pointer row-siswa" onclick="toggleCheckbox('{{ $siswa->id_siswa }}')">
                                            <td class="px-6 py-4" onclick="event.stopPropagation();">
                                                <input type="checkbox" name="siswa_ids[]" value="{{ $siswa->id_siswa }}"
                                                    id="checkbox_{{ $siswa->id_siswa }}"
                                                    class="siswa-checkbox w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500 cursor-pointer transition">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $siswa->id_siswa }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $siswa->nama }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                {{ $siswa->jenis_kelamin }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="flex justify-end pt-6 border-t border-gray-100">
                            <button type="button" onclick="confirmSubmit()"
                                class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg transition font-medium shadow flex items-center text-sm">
                                <i class="fas fa-check-circle mr-2"></i>Proses Menjadi Alumni
                            </button>
                        </div>
                    @endif
                </form>
            @else
                <div class="text-center py-16 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                    <div class="bg-white w-20 h-20 rounded-full flex items-center justify-center mx-auto shadow-sm mb-4">
                        <i class="fas fa-graduation-cap text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-700 mb-1">Pilih Kelas Asal</h3>
                    <p class="text-gray-500">Silakan pilih kelas terlebih dahulu untuk melihat daftar siswa.</p>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            // Script untuk Select All
            const selectAllCheckbox = document.getElementById('selectAll');
            const siswaCheckboxes = document.querySelectorAll('.siswa-checkbox');

            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    siswaCheckboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                        updateRowStyle(checkbox);
                    });
                });

                siswaCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const allChecked = Array.from(siswaCheckboxes).every(c => c.checked);
                        const someChecked = Array.from(siswaCheckboxes).some(c => c.checked);
                        
                        selectAllCheckbox.checked = allChecked;
                        selectAllCheckbox.indeterminate = someChecked && !allChecked;
                        
                        updateRowStyle(this);
                    });
                });
            }
            
            function toggleCheckbox(id) {
                const checkbox = document.getElementById('checkbox_' + id);
                checkbox.checked = !checkbox.checked;
                
                // Trigger change event manually
                const event = new Event('change');
                checkbox.dispatchEvent(event);
            }
            
            function updateRowStyle(checkbox) {
                const row = checkbox.closest('tr');
                if (checkbox.checked) {
                    row.classList.add('bg-green-100');
                    row.classList.remove('hover:bg-green-50');
                } else {
                    row.classList.remove('bg-green-100');
                    row.classList.add('hover:bg-green-50');
                }
            }

            function confirmSubmit() {
                const checkedCount = document.querySelectorAll('.siswa-checkbox:checked').length;
                
                if (checkedCount === 0) {
                    alert('Silakan pilih minimal 1 siswa untuk diproses.');
                    return;
                }
                
                if (confirm('Apakah Anda yakin ingin memproses ' + checkedCount + ' siswa menjadi Alumni? Status mereka akan berubah menjadi Alumni dan Keterangan Status menjadi "Lulus".')) {
                    document.getElementById('formKelulusan').submit();
                }
            }
        </script>
    @endpush
@endsection
