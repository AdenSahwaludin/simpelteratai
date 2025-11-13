<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TK Teratai Kota Cirebon</title>
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

            <!-- Login Form -->
            <div class="bg-white rounded-lg shadow-lg p-8 border-4 border-gray-800">
                <h3 class="text-center text-xl font-bold text-gray-800 mb-6">
                    Masukkan Username dan Password
                </h3>

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('login.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Email Input -->
                    <div class="flex items-center gap-4">
                        <div class="text-2xl text-gray-600">👤</div>
                        <input type="email" name="email" placeholder="Username" value="{{ old('email') }}"
                            class="flex-1 border-2 border-gray-800 px-4 py-3 text-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                            required>
                    </div>

                    <!-- Password Input -->
                    <div class="flex items-center gap-4">
                        <div class="text-2xl text-gray-600">🔒</div>
                        <div class="flex-1 relative">
                            <input type="password" name="password" id="password" placeholder="Password"
                                class="w-full border-2 border-gray-800 px-4 py-3 text-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                                required>
                            <button type="button" onclick="togglePassword()"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-xl cursor-pointer">
                                👁️
                            </button>
                        </div>
                    </div>

                    <!-- Links -->
                    <div class="flex justify-between items-center">
                        <a href="{{ route('forgot.password') }}"
                            class="text-red-600 text-sm underline hover:text-red-800">
                            Lupa password?
                        </a>
                    </div>

                    <!-- Login Button -->
                    <button type="submit"
                        class="w-full bg-blue-300 border-2 border-gray-800 px-6 py-3 text-lg font-bold text-gray-800 rounded-md hover:bg-blue-400 transition duration-200 shadow-md">
                        LOGIN
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
    </script>
</body>

</html>
