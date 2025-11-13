<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - TK Teratai Kota Cirebon</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-blue-200">
    <div class="min-h-screen flex items-center justify-center">
        <div class="w-full max-w-md">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">SELAMAT DATANG</h1>
                <h2 class="text-2xl font-bold text-gray-800 mb-4">TK TERATAI KOTA CIREBON</h2>
                <p class="text-sm text-gray-700 mb-1">
                    Jl. Teratai No. 24 BTN Kalijaga Permai Barat, Kalijaga, Kec Harjamukti, Kota Cirebon
                </p>
                <p class="text-sm text-gray-700">
                    Email : tkterataicrb@gmail.com
                </p>
            </div>

            <!-- Reset Password Form -->
            <div class="bg-white rounded-lg shadow-lg p-8 border-4 border-gray-800">
                <h3 class="text-center text-xl font-bold text-gray-800 mb-6">
                    Reset Password
                </h3>

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('password.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Email Input -->
                    <div class="flex items-center gap-4">
                        <div class="text-2xl text-gray-600">📧</div>
                        <input type="email" name="email" placeholder="Email" value="{{ old('email', $email) }}"
                            class="flex-1 border-2 border-gray-800 px-4 py-3 text-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                            readonly>
                    </div>

                    <!-- Password Input -->
                    <div class="flex items-center gap-4">
                        <div class="text-2xl text-gray-600">🔒</div>
                        <div class="flex-1 relative">
                            <input type="password" name="password" id="password" placeholder="Password Baru"
                                class="w-full border-2 border-gray-800 px-4 py-3 text-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                                required>
                            <button type="button" onclick="togglePassword()"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-xl cursor-pointer">
                                👁️
                            </button>
                        </div>
                    </div>

                    <!-- Confirm Password Input -->
                    <div class="flex items-center gap-4">
                        <div class="text-2xl text-gray-600">🔒</div>
                        <div class="flex-1 relative">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                placeholder="Konfirmasi Password"
                                class="w-full border-2 border-gray-800 px-4 py-3 text-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                                required>
                            <button type="button" onclick="toggleConfirmPassword()"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-xl cursor-pointer">
                                👁️
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-blue-300 border-2 border-gray-800 px-6 py-3 text-lg font-bold text-gray-800 rounded-md hover:bg-blue-400 transition duration-200 shadow-md">
                        RESET PASSWORD
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeButton = event.target;
            const isPasswordType = passwordInput.getAttribute('type') === 'password';

            if (isPasswordType) {
                passwordInput.setAttribute('type', 'text');
                eyeButton.textContent = '👁️‍🗨️';
            } else {
                passwordInput.setAttribute('type', 'password');
                eyeButton.textContent = '👁️';
            }
        }

        function toggleConfirmPassword() {
            const passwordInput = document.getElementById('password_confirmation');
            const eyeButton = event.target;
            const isPasswordType = passwordInput.getAttribute('type') === 'password';

            if (isPasswordType) {
                passwordInput.setAttribute('type', 'text');
                eyeButton.textContent = '👁️‍🗨️';
            } else {
                passwordInput.setAttribute('type', 'password');
                eyeButton.textContent = '👁️';
            }
        }
    </script>
</body>

</html>
