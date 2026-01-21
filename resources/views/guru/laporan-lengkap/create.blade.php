@extends('layouts.dashboard')

@section('title', 'Buat Laporan Lengkap')
@section('nav-color', 'bg-green-600')
@section('sidebar-color', 'bg-green-600')
@section('dashboard-title', 'Buat Laporan Lengkap')
@section('user-name', auth('guru')->user()->nama)
@section('user-role', 'Guru')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Buat Laporan Lengkap Siswa</h2>
            <p class="text-gray-600 mt-1">Isi form untuk membuat laporan komprehensif yang mencakup nilai, kehadiran, dan
                perilaku</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('guru.laporan-lengkap.store') }}" method="POST" class="bg-white rounded-lg shadow p-6">
            @csrf

            <div class="space-y-6">
                <!-- Pilih Siswa -->
                <div>
                    <label for="id_siswa" class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Siswa <span class="text-red-500">*</span>
                    </label>
                    <select name="id_siswa" id="id_siswa" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach ($siswa as $s)
                            <option value="{{ $s->id_siswa }}" {{ old('id_siswa') == $s->id_siswa ? 'selected' : '' }}>
                                {{ $s->nama }} ({{ $s->kelas?->id_kelas ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Periode -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="periode_mulai" class="block text-sm font-medium text-gray-700 mb-2">
                            Periode Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="periode_mulai" id="periode_mulai" value="{{ old('periode_mulai') }}"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    <div>
                        <label for="periode_selesai" class="block text-sm font-medium text-gray-700 mb-2">
                            Periode Selesai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="periode_selesai" id="periode_selesai"
                            value="{{ old('periode_selesai') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Catatan Guru -->
                <div>
                    <label for="catatan_guru" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan Guru <span class="text-red-500">*</span>
                    </label>
                    <textarea name="catatan_guru" id="catatan_guru" rows="4" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="Tulis catatan umum tentang perkembangan siswa...">{{ old('catatan_guru') }}</textarea>
                    <p class="text-sm text-gray-500 mt-1">Catatan umum tentang perkembangan siswa selama periode ini</p>
                </div>

                <!-- Target Pembelajaran -->
                <div>
                    <label for="target_pembelajaran" class="block text-sm font-medium text-gray-700 mb-2">
                        Target Pembelajaran
                    </label>
                    <textarea name="target_pembelajaran" id="target_pembelajaran" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="Apa target yang ingin dicapai siswa?">{{ old('target_pembelajaran') }}</textarea>
                    <p class="text-sm text-gray-500 mt-1">Target yang diharapkan dicapai siswa</p>
                </div>

                <!-- Pencapaian -->
                <div>
                    <label for="pencapaian" class="block text-sm font-medium text-gray-700 mb-2">
                        Pencapaian
                    </label>
                    <textarea name="pencapaian" id="pencapaian" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="Apa yang sudah dicapai siswa?">{{ old('pencapaian') }}</textarea>
                    <p class="text-sm text-gray-500 mt-1">Capaian siswa selama periode ini</p>
                </div>

                <!-- Saran -->
                <div>
                    <label for="saran" class="block text-sm font-medium text-gray-700 mb-2">
                        Saran untuk Orang Tua
                    </label>
                    <textarea name="saran" id="saran" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="Saran untuk orang tua dalam mendukung pembelajaran anak...">{{ old('saran') }}</textarea>
                    <p class="text-sm text-gray-500 mt-1">Saran untuk orang tua dalam mendukung perkembangan anak</p>
                </div>

                <!-- Kirim ke Orang Tua -->
                <div class="bg-green-50 p-4 rounded-lg">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="kirim_ke_ortu" value="1"
                            {{ old('kirim_ke_ortu') ? 'checked' : '' }}
                            class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
                        <span class="ml-3">
                            <span class="font-medium text-gray-900">Kirim ke Orang Tua</span>
                            <span class="block text-sm text-gray-600">Centang untuk langsung mengirimkan laporan ke orang
                                tua. Jika tidak dicentang, laporan akan disimpan sebagai draft.</span>
                        </span>
                    </label>
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                    <div class="flex">
                        <div class="shrink-0">
                            <i class="fas fa-info-circle text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Informasi</h3>
                            <p class="text-sm text-blue-700 mt-1">
                                Data kehadiran, nilai, dan perilaku akan otomatis diambil berdasarkan periode yang Anda
                                pilih.
                                Pastikan periode yang dipilih sudah sesuai. Klik tombol "Preview Data" untuk melihat data
                                yang akan disertakan.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Preview Button -->
                <div>
                    <button type="button" id="btnPreview"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition font-medium">
                        <i class="fas fa-eye mr-2"></i>Preview Data Siswa
                    </button>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex gap-3 mt-8 pt-6 border-t">
                <button type="submit"
                    class="flex-1 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition font-medium">
                    <i class="fas fa-save mr-2"></i>Simpan Laporan
                </button>
                <a href="{{ route('guru.laporan-lengkap.index') }}"
                    class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition font-medium text-center">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
            </div>
        </form>

        <!-- Preview Data Section -->
        <div id="previewSection" class="hidden mt-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Preview Data Siswa</h3>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
                    <!-- Kehadiran -->
                    <div class="bg-white border border-gray-200 rounded-lg">
                        <div class="p-4 border-b border-gray-200">
                            <h4 class="font-bold text-gray-800 flex items-center gap-2">
                                <i class="fas fa-calendar-check text-green-600"></i>
                                Data Kehadiran
                            </h4>
                        </div>
                        <div id="previewKehadiran" class="p-4">
                            <p class="text-gray-500 text-center py-4">Silakan pilih siswa dan periode untuk melihat data
                            </p>
                        </div>
                    </div>

                    <!-- Perilaku -->
                    <div class="bg-white border border-gray-200 rounded-lg">
                        <div class="p-4 border-b border-gray-200">
                            <h4 class="font-bold text-gray-800 flex items-center gap-2">
                                <i class="fas fa-star text-yellow-600"></i>
                                Data Perilaku
                            </h4>
                        </div>
                        <div id="previewPerilaku" class="p-4 max-h-96 overflow-y-auto">
                            <p class="text-gray-500 text-center py-4">Silakan pilih siswa dan periode untuk melihat data
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Nilai -->
                <div class="bg-white border border-gray-200 rounded-lg">
                    <div class="p-4 border-b border-gray-200">
                        <h4 class="font-bold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-book text-green-600"></i>
                            Data Nilai
                        </h4>
                    </div>
                    <div id="previewNilai" class="p-4">
                        <p class="text-gray-500 text-center py-4">Silakan pilih siswa dan periode untuk melihat data</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('btnPreview').addEventListener('click', function() {
                const siswaId = document.getElementById('id_siswa').value;
                const periodeMulai = document.getElementById('periode_mulai').value;
                const periodeSelesai = document.getElementById('periode_selesai').value;

                if (!siswaId || !periodeMulai || !periodeSelesai) {
                    alert('Silakan pilih siswa dan periode terlebih dahulu');
                    return;
                }

                // Show loading
                document.getElementById('previewSection').classList.remove('hidden');
                document.getElementById('previewKehadiran').innerHTML =
                    '<p class="text-gray-500 text-center py-4"><i class="fas fa-spinner fa-spin mr-2"></i>Memuat data...</p>';
                document.getElementById('previewPerilaku').innerHTML =
                    '<p class="text-gray-500 text-center py-4"><i class="fas fa-spinner fa-spin mr-2"></i>Memuat data...</p>';
                document.getElementById('previewNilai').innerHTML =
                    '<p class="text-gray-500 text-center py-4"><i class="fas fa-spinner fa-spin mr-2"></i>Memuat data...</p>';

                // Fetch preview data
                fetch('{{ route('guru.laporan-lengkap.preview') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            siswa_id: siswaId,
                            periode_mulai: periodeMulai,
                            periode_selesai: periodeSelesai
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Render Kehadiran
                        if (data.kehadiran.length === 0) {
                            document.getElementById('previewKehadiran').innerHTML =
                                '<p class="text-gray-500 text-center py-4">Tidak ada data kehadiran</p>';
                        } else {
                            let kehadiranHtml = '<div class="space-y-2">';
                            data.kehadiran.forEach(item => {
                                const colors = {
                                    'hadir': 'bg-green-500',
                                    'izin': 'bg-blue-500',
                                    'sakit': 'bg-yellow-500',
                                    'alpa': 'bg-red-500'
                                };
                                kehadiranHtml += `
                                <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                                    <div class="flex items-center">
                                        <span class="w-3 h-3 ${colors[item.status_kehadiran]} rounded-full mr-2"></span>
                                        <span class="text-sm font-medium capitalize">${item.status_kehadiran}</span>
                                    </div>
                                    <span class="text-sm font-bold">${item.total} kali</span>
                                </div>
                            `;
                            });
                            kehadiranHtml += '</div>';
                            document.getElementById('previewKehadiran').innerHTML = kehadiranHtml;
                        }

                        // Render Perilaku
                        if (data.perilaku.length === 0) {
                            document.getElementById('previewPerilaku').innerHTML =
                                '<p class="text-gray-500 text-center py-4">Tidak ada catatan perilaku</p>';
                        } else {
                            let perilakuHtml = '<div class="space-y-2">';
                            data.perilaku.forEach(item => {
                                perilakuHtml += `
                                <div class="p-2 border border-gray-200 rounded text-sm">
                                    <div class="flex justify-between mb-1">
                                        <div class="flex gap-1">
                                            <span class="px-2 py-0.5 text-xs font-semibold rounded bg-blue-100 text-blue-800">
                                                Sosial: ${item.sosial}
                                            </span>
                                            <span class="px-2 py-0.5 text-xs font-semibold rounded bg-green-100 text-green-800">
                                                Emosional: ${item.emosional}
                                            </span>
                                            <span class="px-2 py-0.5 text-xs font-semibold rounded bg-purple-100 text-purple-800">
                                                Disiplin: ${item.disiplin}
                                            </span>
                                        </div>
                                        <span class="text-xs text-gray-500">${new Date(item.tanggal).toLocaleDateString('id-ID')}</span>
                                    </div>
                                    <p class="text-gray-700 text-xs mb-1">${item.catatan || '-'}</p>
                                    <p class="text-xs text-gray-500">Oleh: ${item.guru.nama}</p>
                                </div>
                            `;
                            });
                            perilakuHtml += '</div>';
                            document.getElementById('previewPerilaku').innerHTML = perilakuHtml;
                        }

                        // Render Nilai
                        if (data.nilai.length === 0) {
                            document.getElementById('previewNilai').innerHTML =
                                '<p class="text-gray-500 text-center py-4">Tidak ada data nilai</p>';
                        } else {
                            let nilaiHtml = '<div class="overflow-x-auto"><table class="min-w-full text-sm">';
                            nilaiHtml +=
                                '<thead class="bg-gray-50"><tr><th class="px-4 py-2 text-left">Mata Pelajaran</th><th class="px-4 py-2 text-left">Nilai</th><th class="px-4 py-2 text-left">Tanggal</th></tr></thead><tbody class="divide-y">';
                            data.nilai.forEach(item => {
                                let badgeClass = 'bg-red-100 text-red-800';
                                if (item.nilai >= 85) badgeClass = 'bg-green-100 text-green-800';
                                else if (item.nilai >= 70) badgeClass = 'bg-blue-100 text-blue-800';
                                else if (item.nilai >= 60) badgeClass = 'bg-yellow-100 text-yellow-800';

                                nilaiHtml += `
                                <tr>
                                    <td class="px-4 py-2">${item.mata_pelajaran.nama_mapel}</td>
                                    <td class="px-4 py-2">
                                        <span class="px-2 py-1 text-xs font-semibold rounded ${badgeClass}">${item.nilai}</span>
                                    </td>
                                    <td class="px-4 py-2 text-gray-600">${new Date(item.tanggal).toLocaleDateString('id-ID')}</td>
                                </tr>
                            `;
                            });
                            nilaiHtml += '</tbody></table></div>';
                            document.getElementById('previewNilai').innerHTML = nilaiHtml;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('previewKehadiran').innerHTML =
                            '<p class="text-red-500 text-center py-4">Gagal memuat data</p>';
                        document.getElementById('previewPerilaku').innerHTML =
                            '<p class="text-red-500 text-center py-4">Gagal memuat data</p>';
                        document.getElementById('previewNilai').innerHTML =
                            '<p class="text-red-500 text-center py-4">Gagal memuat data</p>';
                    });
            });
        </script>
    @endpush
@endsection

@section('sidebar-menu')
    <x-sidebar-menu :guard="'guru'" :currentRoute="request()->route()->getName()" />
@endsection
