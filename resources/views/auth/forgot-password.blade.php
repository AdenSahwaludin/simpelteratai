<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - TK Teratai Kota Cirebon</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-blue-200 min-h-screen">
    <div class="min-h-screen flex items-center justify-center px-4 py-6 sm:px-6 lg:px-8">
        <div class="w-full max-w-md">
            <!-- Header -->
            <div class="text-center mb-6 sm:mb-8">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-1 sm:mb-2">SELAMAT DATANG</h1>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-3 sm:mb-4">TK TERATAI KOTA CIREBON</h2>
                <p class="text-xs sm:text-sm text-gray-700 mb-1 px-2">
                    Jl. Teratai No. 24 BTN Kalijaga Permai Barat, Kalijaga, Kec Harjamukti, Kota Cirebon
                </p>
                <p class="text-xs sm:text-sm text-gray-700">
                    Email : tkterataicrb@gmail.com
                </p>
            </div>

            <!-- Forgot Password Form -->
            <div class="bg-white rounded-lg shadow-lg p-5 sm:p-8 border-4 border-gray-800">
                <h3 class="text-center text-base sm:text-lg font-bold text-gray-800 mb-3 sm:mb-4">
                    Lupa Password
                </h3>

                <p class="text-center text-gray-600 mb-5 sm:mb-6 text-xs sm:text-sm px-2">
                    Masukkan email Anda dan kami akan mengirimkan link untuk reset password.
                </p>

                @if ($errors->any())
                    <div class="mb-4 p-3 sm:p-4 bg-red-100 border border-red-400 text-red-700 rounded text-sm">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                @if (session('status'))
                    <div class="mb-4 p-3 sm:p-4 bg-green-100 border border-green-400 text-green-700 rounded text-sm">
                        {{ session('status') }}
                    </div>
                @endif

                <form action="{{ route('forgot.password.store') }}" method="POST" class="space-y-4 sm:space-y-6">
                    @csrf

                    <!-- Email Input -->
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div class="shrink-0">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}"
                            class="flex-1 border-2 border-gray-800 px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-blue-400"
                            required>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-blue-300 border-2 border-gray-800 px-4 sm:px-6 py-2 sm:py-3 text-sm sm:text-base font-bold text-gray-800 rounded-md hover:bg-blue-400 transition duration-200 shadow-md active:scale-95 sm:active:scale-100">
                        KIRIM LINK RESET
                    </button>

                    <!-- Back to Login Link -->
                    <div class="text-center pt-2">
                        <a href="{{ route('login') }}"
                            class="text-blue-600 text-xs sm:text-sm underline hover:text-blue-800 transition">
                            Kembali ke Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
