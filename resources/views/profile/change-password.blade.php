@extends('layouts.dashboard')

@section('title', 'Ubah Password')

@section('nav-color', $navColor)
@section('sidebar-color', $navColor)
@section('dashboard-title', 'Ubah Password')
@section('user-name', auth($guard)->user()->nama ?? 'User')
@section('user-role', $role)

@section('sidebar-menu')
    <a href="{{ route($dashboardRoute) }}"
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

        <!-- Change Password Card -->
        <div class="bg-white rounded-lg shadow p-8">
            <div class="flex items-center gap-3 mb-6">
                <i class="fas fa-lock text-2xl text-yellow-600"></i>
                <h2 class="text-2xl font-bold text-gray-800">Ubah Password</h2>
            </div>

            <p class="text-gray-600 mb-8">
                <i class="fas fa-info-circle mr-2"></i>Gunakan password yang kuat dengan minimal 8 karakter.
            </p>

            <form action="{{ route('profile.updatePassword') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Current Password -->
                <div>
                    <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-key text-yellow-600 mr-2"></i>Password Saat Ini
                    </label>
                    <div class="relative">
                        <input type="password" id="current_password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 pr-12 @error('current_password') error border-red-500 @enderror"
                            name="current_password" placeholder="Masukkan password saat ini" required />
                        <button type="button"
                            class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
                            onclick="togglePasswordVisibility('current_password', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('current_password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-lock-open text-yellow-600 mr-2"></i>Password Baru
                    </label>
                    <div class="relative">
                        <input type="password" id="password" name="password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 pr-12 @error('password') border-red-500 @enderror"
                            placeholder="Masukkan password baru" required>
                        <button type="button"
                            class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
                            onclick="togglePasswordVisibility('password', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <div class="mt-2 text-xs text-gray-600">
                        <p><i class="fas fa-check text-green-500 mr-1"></i>Minimal 8 karakter</p>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-check-circle text-yellow-600 mr-2"></i>Konfirmasi Password Baru
                    </label>
                    <div class="relative">
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 pr-12"
                            placeholder="Konfirmasi password baru" required>
                        <button type="button"
                            class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
                            onclick="togglePasswordVisibility('password_confirmation', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('profile.edit') }}"
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition font-medium">
                        <i class="fas fa-save mr-2"></i>Ubah Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePasswordVisibility(fieldId, button) {
            const field = document.getElementById(fieldId);
            const isPassword = field.type === 'password';

            field.type = isPassword ? 'text' : 'password';

            if (button.innerHTML.includes('fa-eye"')) {
                button.innerHTML = '<i class="fas fa-eye-slash"></i>';
            } else {
                button.innerHTML = '<i class="fas fa-eye\"></i>';
            }
        }
    </script>

    <style>
        :root {
            --profile-color: #2563eb;
            --menu-bg: #f3f4f6;
            --menu-color: #374151;
            --menu-icon-color: {{ $iconColor }};
        }
    </style>
@endsection
