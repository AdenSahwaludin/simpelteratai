<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - TK Teratai Kota Cirebon</title>
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

            <!-- Forgot Password Form -->
            <div class="bg-white rounded-lg shadow-lg p-8 border-4 border-gray-800">
                <h3 class="text-center text-xl font-bold text-gray-800 mb-6">
                    Lupa Password
                </h3>

                <p class="text-center text-gray-600 mb-6 text-sm">
                    Masukkan email Anda dan kami akan mengirimkan link untuk reset password.
                </p>

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                @if (session('status'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('status') }}
                    </div>
                @endif

                <form action="{{ route('forgot.password.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Email Input -->
                    <div class="flex items-center gap-4">
                        <div class="text-2xl text-gray-600">📧</div>
                        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}"
                            class="flex-1 border-2 border-gray-800 px-4 py-3 text-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                            required>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-blue-300 border-2 border-gray-800 px-6 py-3 text-lg font-bold text-gray-800 rounded-md hover:bg-blue-400 transition duration-200 shadow-md">
                        KIRIM LINK RESET
                    </button>

                    <!-- Back to Login Link -->
                    <div class="text-center">
                        <a href="{{ route('login') }}" class="text-blue-600 text-sm underline hover:text-blue-800">
                            Kembali ke Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
