<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - TK Teratai Kota Cirebon</title>
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

            <!-- Reset Password Form -->
            <div class="bg-white rounded-lg shadow-lg p-5 sm:p-8 border-4 border-gray-800">
                <h3 class="text-center text-base sm:text-lg font-bold text-gray-800 mb-5 sm:mb-6">
                    Reset Password
                </h3>

                @if ($errors->any())
                    <div class="mb-4 p-3 sm:p-4 bg-red-100 border border-red-400 text-red-700 rounded text-sm">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('password.store') }}" method="POST" class="space-y-4 sm:space-y-6">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Email Input -->
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div class="shrink-0">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <input type="email" name="email" placeholder="Email" value="{{ old('email', $email) }}"
                            class="flex-1 border-2 border-gray-800 px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-blue-400"
                            readonly>
                    </div>

                    <!-- Password Input -->
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div class="shrink-0">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <div class="flex-1 relative">
                            <input type="password" name="password" id="password" placeholder="Password Baru"
                                class="w-full border-2 border-gray-800 px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-blue-400"
                                required>
                            <button type="button" onclick="togglePassword()"
                                class="absolute right-2 sm:right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 transition">
                                <!-- Eye Open SVG -->
                                <svg id="eyeOpen" class="w-5 h-5 sm:w-6 sm:h-6 hidden" viewBox="0 0 24 24"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                <!-- Eye Closed SVG -->
                                <svg id="eyeClosed" class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2 2L22 22" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M6.71277 6.7226C3.66479 8.79527 2 12 2 12C2 12 5.63636 19 12 19C14.0503 19 15.8174 18.2734 17.2711 17.2884M11 5.05822C11.3254 5.02013 11.6588 5 12 5C18.3636 5 22 12 22 12C22 12 21.3082 13.3317 20 14.8335"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M14 14.2362C13.4692 14.7112 12.7684 15.0001 12 15.0001C10.3431 15.0001 9 13.657 9 12.0001C9 11.1764 9.33193 10.4303 9.86932 9.88818"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Confirm Password Input -->
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div class="shrink-0">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <div class="flex-1 relative">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                placeholder="Konfirmasi Password"
                                class="w-full border-2 border-gray-800 px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-blue-400"
                                required>
                            <button type="button" onclick="toggleConfirmPassword()"
                                class="absolute right-2 sm:right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 transition">
                                <!-- Eye Open SVG -->
                                <svg id="eyeOpenConfirm" class="w-5 h-5 sm:w-6 sm:h-6 hidden" viewBox="0 0 24 24"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                <!-- Eye Closed SVG -->
                                <svg id="eyeClosedConfirm" class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 24 24"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2 2L22 22" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M6.71277 6.7226C3.66479 8.79527 2 12 2 12C2 12 5.63636 19 12 19C14.0503 19 15.8174 18.2734 17.2711 17.2884M11 5.05822C11.3254 5.02013 11.6588 5 12 5C18.3636 5 22 12 22 12C22 12 21.3082 13.3317 20 14.8335"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M14 14.2362C13.4692 14.7112 12.7684 15.0001 12 15.0001C10.3431 15.0001 9 13.657 9 12.0001C9 11.1764 9.33193 10.4303 9.86932 9.88818"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-blue-300 border-2 border-gray-800 px-4 sm:px-6 py-2 sm:py-3 text-sm sm:text-base font-bold text-gray-800 rounded-md hover:bg-blue-400 transition duration-200 shadow-md active:scale-95 sm:active:scale-100">
                        RESET PASSWORD
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eyeOpen');
            const eyeClosed = document.getElementById('eyeClosed');
            const isPasswordType = passwordInput.getAttribute('type') === 'password';

            if (isPasswordType) {
                passwordInput.setAttribute('type', 'text');
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            } else {
                passwordInput.setAttribute('type', 'password');
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            }
        }

        function toggleConfirmPassword() {
            const passwordInput = document.getElementById('password_confirmation');
            const eyeOpen = document.getElementById('eyeOpenConfirm');
            const eyeClosed = document.getElementById('eyeClosedConfirm');
            const isPasswordType = passwordInput.getAttribute('type') === 'password';

            if (isPasswordType) {
                passwordInput.setAttribute('type', 'text');
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            } else {
                passwordInput.setAttribute('type', 'password');
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            }
        }
    </script>
</body>

</html>
