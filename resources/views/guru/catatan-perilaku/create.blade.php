@extends('layouts.dashboard')

@section('title', 'Tambah Catatan Perilaku')
@section('nav-color', 'bg-green-600')
@section('sidebar-color', 'bg-green-600')
@section('dashboard-title', 'Tambah Catatan Perilaku')
@section('user-name', auth('guru')->user()->nama)
@section('user-role', 'Guru')

@section('sidebar-menu')
    <x-sidebar-menu :guard="'guru'" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Tambah Catatan Perilaku</h2>
                <p class="text-gray-600 mt-2">Catat perilaku siswa untuk pemantauan perkembangan</p>
            </div>
            <a href="{{ route('guru.catatan-perilaku.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-300">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('guru.catatan-perilaku.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Nama Anak -->
                    <div>
                        <label for="id_siswa" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama anak
                        </label>
                        <select name="id_siswa" id="id_siswa" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('id_siswa') border-red-500 @enderror">
                            <option value="">-- Pilih Siswa --</option>
                            @foreach ($siswaList as $siswa)
                                <option value="{{ $siswa->id_siswa }}" data-nis="{{ $siswa->id_siswa }}"
                                    data-kelas="{{ $siswa->kelas->id_kelas ?? 'N/A' }}"
                                    {{ old('id_siswa', request('id_siswa')) == $siswa->id_siswa ? 'selected' : '' }}>
                                    {{ $siswa->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_siswa')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NIS -->
                    <div>
                        <label for="nis" class="block text-sm font-medium text-gray-700 mb-2">
                            NIS
                        </label>
                        <input type="text" id="nis" readonly
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Kelas -->
                    <div>
                        <label for="kelas" class="block text-sm font-medium text-gray-700 mb-2">
                            Kelas
                        </label>
                        <input type="text" id="kelas" readonly
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100">
                    </div>

                    <!-- Tanggal -->
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal
                        </label>
                        <input type="date" name="tanggal" id="tanggal" required
                            value="{{ old('tanggal', date('Y-m-d')) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('tanggal') border-red-500 @enderror">
                        @error('tanggal')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Guru Pengamat -->
                <div class="mb-6">
                    <label for="guru_pengamat" class="block text-sm font-medium text-gray-700 mb-2">
                        Guru pengamat
                    </label>
                    <input type="text" id="guru_pengamat" readonly value="{{ auth('guru')->user()->nama }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100">
                </div>

                <!-- Sub-Aspek/Indikator Penilaian -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Sub-Aspek/Indikator</h3>

                    <!-- Sosial -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sosial</label>
                        <div class="flex items-center gap-6">
                            <label class="flex items-center">
                                <input type="radio" name="sosial" value="Baik" required
                                    {{ old('sosial') == 'Baik' ? 'checked' : '' }}
                                    class="w-4 h-4 text-green-600 focus:ring-green-500 @error('sosial') border-red-500 @enderror">
                                <span class="ml-2 text-gray-700">Baik</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="sosial" value="Perlu dibina" required
                                    {{ old('sosial') == 'Perlu dibina' ? 'checked' : '' }}
                                    class="w-4 h-4 text-green-600 focus:ring-green-500 @error('sosial') border-red-500 @enderror">
                                <span class="ml-2 text-gray-700">Perlu dibina</span>
                            </label>
                        </div>
                        @error('sosial')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Emosional -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Emosional</label>
                        <div class="flex items-center gap-6">
                            <label class="flex items-center">
                                <input type="radio" name="emosional" value="Baik" required
                                    {{ old('emosional') == 'Baik' ? 'checked' : '' }}
                                    class="w-4 h-4 text-green-600 focus:ring-green-500 @error('emosional') border-red-500 @enderror">
                                <span class="ml-2 text-gray-700">Baik</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="emosional" value="Perlu dibina" required
                                    {{ old('emosional') == 'Perlu dibina' ? 'checked' : '' }}
                                    class="w-4 h-4 text-green-600 focus:ring-green-500 @error('emosional') border-red-500 @enderror">
                                <span class="ml-2 text-gray-700">Perlu dibina</span>
                            </label>
                        </div>
                        @error('emosional')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Disiplin -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Disiplin</label>
                        <div class="flex items-center gap-6">
                            <label class="flex items-center">
                                <input type="radio" name="disiplin" value="Baik" required
                                    {{ old('disiplin') == 'Baik' ? 'checked' : '' }}
                                    class="w-4 h-4 text-green-600 focus:ring-green-500 @error('disiplin') border-red-500 @enderror">
                                <span class="ml-2 text-gray-700">Baik</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="disiplin" value="Perlu dibina" required
                                    {{ old('disiplin') == 'Perlu dibina' ? 'checked' : '' }}
                                    class="w-4 h-4 text-green-600 focus:ring-green-500 @error('disiplin') border-red-500 @enderror">
                                <span class="ml-2 text-gray-700">Perlu dibina</span>
                            </label>
                        </div>
                        @error('disiplin')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Catatan -->
                <div class="mb-6">
                    <label for="catatan_perilaku" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan
                    </label>
                    <textarea name="catatan_perilaku" id="catatan_perilaku" rows="4" required
                        placeholder="Tuliskan catatan perilaku siswa..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('catatan_perilaku') border-red-500 @enderror">{{ old('catatan_perilaku') }}</textarea>
                    @error('catatan_perilaku')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex gap-4 justify-end">
                    <button type="button" onclick="window.location='{{ route('guru.catatan-perilaku.index') }}'"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition-colors duration-300">
                        Hapus
                    </button>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors duration-300">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Auto-fill NIS dan Kelas saat siswa dipilih
        document.getElementById('id_siswa').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const nis = selectedOption.getAttribute('data-nis');
            const kelas = selectedOption.getAttribute('data-kelas');

            document.getElementById('nis').value = nis || '';
            document.getElementById('kelas').value = kelas || '';
        });

        // Show selected file name
        document.getElementById('file_lampiran').addEventListener('change', function() {
            const fileName = this.files[0] ? this.files[0].name : 'Tidak ada file yang dipilih';
            document.getElementById('file-name').textContent = fileName;
        });
    </script>
@endsection
