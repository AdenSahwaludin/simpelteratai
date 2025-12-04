@extends('layouts.dashboard')

@section('title', 'Edit Laporan Lengkap')
@section('nav-color', 'bg-green-600')
@section('sidebar-color', 'bg-green-600')
@section('dashboard-title', 'Edit Laporan Lengkap')
@section('user-name', auth('guru')->user()->nama)
@section('user-role', 'Guru')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Edit Laporan Lengkap</h2>
            <p class="text-gray-600 mt-1">Perbarui laporan komprehensif siswa</p>
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

        <form action="{{ route('guru.laporan-lengkap.update', $laporan->id_laporan_lengkap) }}" method="POST"
            class="bg-white rounded-lg shadow p-6">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Info Siswa -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Siswa</h3>
                    <p class="text-lg font-semibold text-gray-800">{{ $laporan->siswa->nama }}
                        ({{ $laporan->siswa->kelas }})</p>
                </div>

                <!-- Periode -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="periode_mulai" class="block text-sm font-medium text-gray-700 mb-2">
                            Periode Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="periode_mulai" id="periode_mulai"
                            value="{{ old('periode_mulai', $laporan->periode_mulai) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    <div>
                        <label for="periode_selesai" class="block text-sm font-medium text-gray-700 mb-2">
                            Periode Selesai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="periode_selesai" id="periode_selesai"
                            value="{{ old('periode_selesai', $laporan->periode_selesai) }}" required
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
                        placeholder="Tulis catatan umum tentang perkembangan siswa...">{{ old('catatan_guru', $laporan->catatan_guru) }}</textarea>
                    <p class="text-sm text-gray-500 mt-1">Catatan umum tentang perkembangan siswa selama periode ini</p>
                </div>

                <!-- Target Pembelajaran -->
                <div>
                    <label for="target_pembelajaran" class="block text-sm font-medium text-gray-700 mb-2">
                        Target Pembelajaran
                    </label>
                    <textarea name="target_pembelajaran" id="target_pembelajaran" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="Apa target yang ingin dicapai siswa?">{{ old('target_pembelajaran', $laporan->target_pembelajaran) }}</textarea>
                    <p class="text-sm text-gray-500 mt-1">Target yang diharapkan dicapai siswa</p>
                </div>

                <!-- Pencapaian -->
                <div>
                    <label for="pencapaian" class="block text-sm font-medium text-gray-700 mb-2">
                        Pencapaian
                    </label>
                    <textarea name="pencapaian" id="pencapaian" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="Apa yang sudah dicapai siswa?">{{ old('pencapaian', $laporan->pencapaian) }}</textarea>
                    <p class="text-sm text-gray-500 mt-1">Capaian siswa selama periode ini</p>
                </div>

                <!-- Saran -->
                <div>
                    <label for="saran" class="block text-sm font-medium text-gray-700 mb-2">
                        Saran untuk Orang Tua
                    </label>
                    <textarea name="saran" id="saran" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="Saran yang bisa dilakukan orang tua di rumah...">{{ old('saran', $laporan->saran) }}</textarea>
                    <p class="text-sm text-gray-500 mt-1">Saran untuk orang tua dalam mendukung perkembangan anak</p>
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-info-circle text-blue-600 mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-blue-900 mb-1">Informasi</h4>
                            <p class="text-sm text-blue-800">Data kehadiran, nilai, dan perilaku siswa akan otomatis diambil
                                berdasarkan periode yang dipilih dan akan ditampilkan dalam laporan.</p>
                        </div>
                    </div>
                </div>

                <!-- Kirim ke Orang Tua -->
                <div class="flex items-start gap-3">
                    <input type="checkbox" name="kirim_ke_ortu" id="kirim_ke_ortu" value="1"
                        {{ old('kirim_ke_ortu', $laporan->dikirim_ke_ortu) ? 'checked' : '' }}
                        class="mt-1 h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                    <div>
                        <label for="kirim_ke_ortu" class="block text-sm font-medium text-gray-700">
                            Kirim ke Orang Tua
                        </label>
                        <p class="text-sm text-gray-500">Centang jika ingin langsung mengirim laporan ini ke orang tua</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 pt-4">
                    <button type="submit"
                        class="flex-1 sm:flex-initial bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition font-medium">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                    <a href="{{ route('guru.laporan-lengkap.index') }}"
                        class="flex-1 sm:flex-initial bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition font-medium text-center">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection
