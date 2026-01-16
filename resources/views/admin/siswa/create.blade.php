@extends('layouts.dashboard')

@section('title', 'Tambah Siswa')
@section('nav-color', 'bg-blue-600')
@section('sidebar-color', 'bg-blue-600')
@section('dashboard-title', 'Tambah Siswa')
@section('user-name', auth('admin')->user()->nama)
@section('user-role', 'Admin')

@section('sidebar-menu')
    <x-sidebar-menu :guard="'admin'" :currentRoute="request()->route()->getName()" />
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            height: 42px;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 0.625rem 1rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 26px;
            padding-left: 0;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #3b82f6;
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .modal {
            transition: opacity 0.25s ease;
        }

        .modal-content {
            transform: translateY(-50px);
            transition: transform 0.3s ease;
        }

        .modal.show .modal-content {
            transform: translateY(0);
        }
    </style>
@endpush

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="mb-4">
            <a href="{{ route('admin.siswa.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i>
                <span>Kembali ke Daftar Siswa</span>
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 md:p-8">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-user-plus text-blue-600 mr-3"></i>
                    Tambah Data Siswa
                </h2>
                <p class="text-sm text-gray-600 mt-1">Lengkapi form di bawah untuk menambahkan siswa baru</p>
            </div>

            <form action="{{ route('admin.siswa.store') }}" method="POST" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Siswa <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama') border-red-500 @enderror"
                            placeholder="Masukkan nama lengkap" required autofocus>
                        @error('nama')
                            <p class="text-red-500 text-xs mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis Kelamin <span class="text-gray-400 text-xs">(Opsional)</span>
                        </label>
                        <select name="jenis_kelamin" id="jenis_kelamin"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('jenis_kelamin') border-red-500 @enderror">
                            <option value="">-- Pilih --</option>
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <p class="text-red-500 text-xs mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 mb-2">
                            Tempat Lahir <span class="text-gray-400 text-xs">(Opsional)</span>
                        </label>
                        <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tempat_lahir') border-red-500 @enderror"
                            placeholder="Contoh: Bandung">
                        @error('tempat_lahir')
                            <p class="text-red-500 text-xs mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Lahir <span class="text-gray-400 text-xs">(Opsional)</span>
                        </label>
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tanggal_lahir') border-red-500 @enderror">
                        @error('tanggal_lahir')
                            <p class="text-red-500 text-xs mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="kelas" class="block text-sm font-medium text-gray-700 mb-2">
                            Kelas <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="kelas" id="kelas" value="{{ old('kelas') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('kelas') border-red-500 @enderror"
                            placeholder="Contoh: A, B, 5A" required>
                        @error('kelas')
                            <p class="text-red-500 text-xs mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">
                        Alamat <span class="text-red-500">*</span>
                    </label>
                    <textarea name="alamat" id="alamat" rows="3"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('alamat') border-red-500 @enderror"
                        placeholder="Masukkan alamat lengkap" required>{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <p class="text-red-500 text-xs mt-1 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="id_orang_tua" class="block text-sm font-medium text-gray-700 mb-2">
                        Orang Tua <span class="text-red-500">*</span>
                    </label>
                    <div class="flex gap-2">
                        <div class="flex-1">
                            <select name="id_orang_tua" id="id_orang_tua"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('id_orang_tua') border-red-500 @enderror"
                                required>
                                <option value="">-- Pilih Orang Tua --</option>
                                @foreach ($orangTuaList as $orangTua)
                                    <option value="{{ $orangTua->id_orang_tua }}"
                                        {{ old('id_orang_tua') == $orangTua->id_orang_tua ? 'selected' : '' }}>
                                        {{ $orangTua->nama }} ({{ $orangTua->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="button" id="btnTambahOrangTua"
                            class="px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg transition font-medium whitespace-nowrap">
                            <i class="fas fa-plus mr-1"></i> Tambah Baru
                        </button>
                    </div>
                    @error('id_orang_tua')
                        <p class="text-red-500 text-xs mt-1 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200">
                    <button type="submit"
                        class="flex-1 sm:flex-none bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg transition font-medium shadow-sm hover:shadow-md">
                        <i class="fas fa-save mr-2"></i>Simpan Data
                    </button>
                    <a href="{{ route('admin.siswa.index') }}"
                        class="flex-1 sm:flex-none text-center bg-gray-500 hover:bg-gray-600 text-white px-8 py-3 rounded-lg transition font-medium">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Tambah Orang Tua -->
    <div id="modalTambahOrangTua"
        class="modal fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="modal-content bg-white rounded-lg shadow-xl w-full max-w-md mx-4 max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 rounded-t-lg">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-user-plus text-green-600 mr-2"></i>
                        Tambah Orang Tua Baru
                    </h3>
                    <button type="button" id="btnCloseModal" class="text-gray-400 hover:text-gray-600 transition">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <form id="formTambahOrangTua" class="p-6 space-y-4">
                @csrf
                <div>
                    <label for="modal_nama" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Orang Tua <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="modal_nama" name="nama"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="Masukkan nama lengkap" required>
                    <p class="text-red-500 text-xs mt-1 hidden" id="error_nama"></p>
                </div>

                <div>
                    <label for="modal_email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="modal_email" name="email"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="contoh@email.com" required>
                    <p class="text-red-500 text-xs mt-1 hidden" id="error_email"></p>
                </div>

                <div>
                    <label for="modal_no_telpon" class="block text-sm font-medium text-gray-700 mb-2">
                        No. Telepon <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="modal_no_telpon" name="no_telpon"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="08xxxxxxxxxx" required>
                    <p class="text-red-500 text-xs mt-1 hidden" id="error_no_telpon"></p>
                </div>

                <div>
                    <label for="modal_password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" id="modal_password" name="password"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="Minimal 6 karakter" required>
                    <p class="text-red-500 text-xs mt-1 hidden" id="error_password"></p>
                </div>

                <div class="flex gap-3 pt-4 border-t border-gray-200">
                    <button type="submit" id="btnSubmitOrangTua"
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg transition font-medium">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                    <button type="button" id="btnBatalModal"
                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-6 py-2.5 rounded-lg transition font-medium">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Setup CSRF token for AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Initialize Select2 with AJAX
            $('#id_orang_tua').select2({
                ajax: {
                    url: '{{ route('admin.siswa.search-orangtua') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results
                        };
                    },
                    cache: true
                },
                placeholder: '-- Pilih atau Cari Orang Tua --',
                minimumInputLength: 0,
                allowClear: false,
                language: {
                    inputTooShort: function() {
                        return 'Ketik untuk mencari...';
                    },
                    searching: function() {
                        return 'Mencari...';
                    },
                    noResults: function() {
                        return 'Tidak ada hasil';
                    }
                }
            });

            // Trigger initial load
            $('#id_orang_tua').trigger('change');

            // Modal functions
            const modal = $('#modalTambahOrangTua');
            const form = $('#formTambahOrangTua');

            // Open modal
            $('#btnTambahOrangTua').click(function() {
                modal.removeClass('hidden').addClass('flex show');
            });

            // Close modal
            function closeModal() {
                modal.removeClass('show');
                setTimeout(() => {
                    modal.addClass('hidden').removeClass('flex');
                    form[0].reset();
                    $('.text-red-500').addClass('hidden');
                    $('.border-red-500').removeClass('border-red-500');
                }, 300);
            }

            $('#btnCloseModal, #btnBatalModal').click(closeModal);

            // Close modal when clicking outside
            modal.click(function(e) {
                if (e.target === this) {
                    closeModal();
                }
            });

            // Submit form via AJAX
            form.submit(function(e) {
                e.preventDefault();

                // Clear previous errors
                $('.text-red-500').addClass('hidden');
                $('.border-red-500').removeClass('border-red-500');

                const submitBtn = $('#btnSubmitOrangTua');
                const originalText = submitBtn.html();
                submitBtn.prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...');

                $.ajax({
                    url: '{{ route('admin.siswa.store-orangtua') }}',
                    type: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        if (response.success) {
                            // Create new option and select it
                            const newOption = new Option(
                                response.data.text,
                                response.data.id,
                                true,
                                true
                            );
                            $('#id_orang_tua').append(newOption).trigger('change');

                            // Close modal
                            closeModal();

                            // Show success message
                            alert('Data orang tua berhasil ditambahkan dan telah dipilih!');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;

                            // Display errors
                            $.each(errors, function(key, value) {
                                const errorElement = $('#error_' + key);
                                const inputElement = $('#modal_' + key);

                                errorElement.text(value[0]).removeClass('hidden');
                                inputElement.addClass('border-red-500');
                            });
                        } else if (xhr.status === 500) {
                            const message = xhr.responseJSON && xhr.responseJSON.message ?
                                xhr.responseJSON.message :
                                'Terjadi kesalahan server. Silakan coba lagi.';
                            alert(message);
                        } else {
                            alert('Terjadi kesalahan. Silakan coba lagi.');
                        }
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false).html(originalText);
                    }
                });
            });
        });
    </script>
@endpush
