@extends('layouts.dashboard')

@section('title', 'Edit Profil')

@section('nav-color', $navColor)
@section('sidebar-color', $navColor)
@section('dashboard-title', 'Edit Profil')
@section('user-name', $user->nama ?? 'User')
@section('user-role', $role)

@section('sidebar-menu')
    <a href="@if ($guard === 'admin') {{ route('admin.dashboard') }} @elseif($guard === 'guru') {{ route('guru.dashboard') }} @elseif($guard === 'orangtua') {{ route('orangtua.dashboard') }} @endif"
        class="sidebar-menu-item flex items-center gap-3 px-4 py-2 text-gray-700 rounded-lg transition font-medium"
        style="background-color: var(--menu-bg); color: var(--menu-color)">
        <i class="fas fa-chart-bar icon" style="color: var(--menu-icon-color)"></i>
        <span>Dashboard</span>
    </a>

    <div class="px-4 py-2">
        <p class="sidebar-category-label text-xs font-semibold text-gray-500 uppercase tracking-wider">Akun</p>
    </div>

    <a href="{{ route('profile.edit') }}"
        class="sidebar-menu-item flex items-center gap-3 px-4 py-2 text-gray-700 rounded-lg transition"
        style="background-color: var(--menu-bg); color: var(--menu-color)">
        <i class="fas fa-user-edit icon" style="color: var(--menu-icon-color)"></i>
        <span>Edit Profil</span>
    </a>

    <a href="{{ route('profile.password') }}"
        class="sidebar-menu-item flex items-center gap-3 px-4 py-2 text-gray-700 rounded-lg transition"
        style="background-color: var(--menu-bg); color: var(--menu-color)">
        <i class="fas fa-lock icon" style="color: var(--menu-icon-color)"></i>
        <span>Ubah Password</span>
    </a>
@endsection

@section('content')
    <div class="max-w-2xl mx-auto">
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex items-start gap-3">
                    <i class="fas fa-exclamation-circle text-red-600 mt-1"></i>
                    <div>
                        <h3 class="font-semibold text-red-800 mb-2">Kesalahan Validasi</h3>
                        <ul class="text-red-700 text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex items-start gap-3">
                    <i class="fas fa-check-circle text-green-600 mt-1"></i>
                    <div>
                        <h3 class="font-semibold text-green-800">{{ session('success') }}</h3>
                    </div>
                </div>
            </div>
        @endif

        <!-- Edit Profile Card -->
        <div class="bg-white rounded-lg shadow p-8 mb-6">
            <div class="flex items-center gap-3 mb-6">
                <i class="fas fa-user-circle text-2xl" style="color: var(--profile-color)"></i>
                <h2 class="text-2xl font-bold text-gray-800">Edit Profil</h2>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Nama -->
                <div>
                    <label for="nama" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-user text-blue-600 mr-2"></i>Nama Lengkap
                    </label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama', $user->nama) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nama') border-red-500 @enderror"
                        placeholder="Masukkan nama lengkap Anda" required>
                    @error('nama')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-envelope text-blue-600 mr-2"></i>Email
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                        placeholder="Masukkan email Anda" required>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <!-- No Telpon -->

                <div>
                    <label for="no_telpon" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-phone text-blue-600 mr-2"></i>Nomor Telepon
                    </label>
                    <input type="tel" id="no_telpon" name="no_telpon"
                        value="{{ old('no_telpon', $user->no_telpon ?? '') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Masukkan nomor telepon (opsional)">
                    @error('no_telpon')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-6 border-t border-gray-200">
                    <a href="@if ($guard === 'admin') {{ route('admin.dashboard') }} @elseif($guard === 'guru') {{ route('guru.dashboard') }} @elseif($guard === 'orangtua') {{ route('orangtua.dashboard') }} @endif"
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-medium">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- Change Password Card -->
        <div class="bg-white rounded-lg shadow p-8">
            <div class="flex items-center gap-3 mb-6">
                <i class="fas fa-lock text-2xl text-yellow-600"></i>
                <h2 class="text-2xl font-bold text-gray-800">Ubah Password</h2>
            </div>

            <p class="text-gray-600 mb-4">
                <i class="fas fa-info-circle mr-2"></i>Ubah password Anda untuk meningkatkan keamanan akun.
            </p>

            <a href="{{ route('profile.password') }}"
                class="inline-block px-6 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition font-medium">
                <i class="fas fa-arrow-right mr-2"></i>Ubah Password
            </a>
        </div>
    </div>

    <style>
        :root {
            --profile-color: #2563eb;
            --menu-bg: #f3f4f6;
            --menu-color: #374151;
            --menu-icon-color: #2563eb;
        }
    </style>
    <script>
        document.documentElement.style.setProperty('--menu-icon-color', '{{ $iconColor }}');
    </script>
@endsection
