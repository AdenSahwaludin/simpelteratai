<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - TK Teratai Kota Cirebon</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="@yield('nav-color', 'bg-blue-600') text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold">@yield('dashboard-title')</h1>
                <p class="text-sm text-gray-100">TK Teratai Kota Cirebon</p>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded transition">
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        @if (session('status'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Welcome Card -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">
                Selamat Datang, @yield('user-name')!
            </h2>
            <p class="text-gray-600">
                @yield('welcome-message')
            </p>
        </div>

        <!-- Content Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @yield('content')
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white text-center py-4 mt-8">
        <p>&copy; 2025 TK Teratai Kota Cirebon. All rights reserved.</p>
    </footer>
</body>

</html>
