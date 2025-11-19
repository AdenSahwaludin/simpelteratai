<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - TK Teratai Kota Cirebon</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside id="sidebar"
            class="w-64 bg-white shadow-lg transition-all duration-300 flex flex-col overflow-hidden fixed lg:relative left-0 top-0 h-full z-40 lg:flex whitespace-nowrap"
            style="display: none;" data-collapsed="false">
            <!-- Sidebar Header -->
            <div class="@yield('sidebar-color', 'bg-blue-600') text-white p-4 shrink-0 min-h-20 ">
                <div id="sidebar-header-content" class="transition-opacity duration-300">
                    <h2 class="text-lg font-bold">@yield('school-name', 'TK Teratai')</h2>
                    <p class="text-xs text-gray-200">Kota Cirebon</p>
                </div>
            </div>

            <!-- Sidebar Content -->
            <nav id="sidebar-menu"
                class="flex-1 overflow-y-auto px-4 py-6 space-y-2 transition-opacity duration-300 whitespace-nowrap">
                @yield('sidebar-menu')
            </nav>

            <!-- Sidebar Footer -->
            <div class="shadow-t shadow-gray-300 shadow p-4 shrink-0 transition-opacity duration-300">
                <div id="sidebar-footer-content" class="text-sm text-gray-600 mb-3">
                    <p class="font-semibold">@yield('user-role')</p>
                    <p class="truncate text-xs">@yield('user-name')</p>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded text-sm transition font-medium">
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col sm:ml-0">
            <!-- Top Navigation Bar -->
            <header class="@yield('nav-color', 'bg-blue-600') text-white shadow-md sticky top-0 z-30">
                <div class="px-4 sm:px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <!-- Mobile Hamburger -->
                        <button id="toggle-sidebar"
                            class="p-2 hover:bg-white/20 hover:bg-opacity-20 rounded-lg transition lg:hidden text-white">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <!-- Desktop Collapse Button -->
                        <button id="sidebar-toggle-btn"
                            class="hidden lg:block p-2 hover:bg-white/20 hover:bg-opacity-20 rounded-lg transition text-white">
                            <i class="fas fa-bars text-xl" id="sidebar-toggle-icon"></i>
                        </button>
                        <div>
                            <h1 class="text-lg sm:text-2xl font-bold">@yield('dashboard-title')</h1>
                            <p class="text-xs sm:text-sm text-gray-100">Dashboard</p>
                        </div>
                    </div>

                    <!-- Profile Dropdown -->
                    <div class="relative" id="profile-dropdown-container">
                        <button id="profile-dropdown-btn"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-white/20 hover:bg-opacity-20 transition">
                            <i class="fas fa-user-circle text-white text-2xl"></i>
                            <span class="text-sm font-medium hidden sm:inline">@yield('user-name')</span>
                            <i class="fas fa-chevron-down text-white text-sm transition-transform duration-300"
                                id="profile-dropdown-arrow"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="profile-dropdown-menu"
                            class="absolute right-0 mt-2 w-48 bg-white text-gray-800 rounded-lg shadow-xl opacity-0 invisible transition-all duration-200 z-50">
                            <div class="px-4 py-3 border-b">
                                <p class="text-sm font-semibold">@yield('user-name')</p>
                                <p class="text-xs text-gray-500">@yield('user-role')</p>
                                @if (Auth::guard('guru')->check())
                                    <p class="text-xs text-gray-600 mt-1">NIP:
                                        {{ Auth::guard('guru')->user()->nip ?? '-' }}</p>
                                @endif
                            </div>
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-sm hover:bg-gray-100 transition">
                                <i class="fas fa-user-edit text-blue-600 mr-2"></i>
                                Edit Profil
                            </a>
                            <a href="{{ route('profile.password') }}"
                                class="block px-4 py-2 text-sm hover:bg-gray-100 transition">
                                <i class="fas fa-lock text-yellow-600 mr-2"></i>
                                Ganti Password
                            </a>
                            <hr class="my-2">
                            <form action="{{ route('logout') }}" method="POST" class="block">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                    <i class="fas fa-sign-out-alt text-red-600 mr-2"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 flex flex-col overflow-auto">
                <div class="flex-1 p-4 sm:p-6 lg:p-8">
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

                    <!-- Content Section -->
                    @yield('content')
                </div>
                <!-- Footer -->
                <footer class="@yield('nav-color', 'bg-blue-600') text-white text-center py-4 shrink-0 mt-auto">
                    <p class="text-xs sm:text-sm">&copy; {{ date('Y') }} TK Teratai Kota Cirebon. All rights
                        reserved.
                    </p>
                </footer>
            </main>
        </div>
    </div>

    <!-- Sidebar Overlay for Mobile -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-30 hidden lg:hidden transition-all duration-300">
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggleBtn = document.getElementById('sidebar-toggle-btn');
        const sidebarToggleIcon = document.getElementById('sidebar-toggle-icon');
        const hamburgerToggleBtn = document.getElementById('toggle-sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const profileDropdownBtn = document.getElementById('profile-dropdown-btn');
        const profileDropdownMenu = document.getElementById('profile-dropdown-menu');
        const profileDropdownArrow = document.getElementById('profile-dropdown-arrow');
        const profileDropdownContainer = document.getElementById('profile-dropdown-container');

        // Initialize sidebar state
        let isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';

        // Restore sidebar state on page load
        if (isCollapsed && window.innerWidth >= 1024) {
            collapseSidebar();
        }

        // Show sidebar on large screens
        if (window.innerWidth >= 1024) {
            sidebar.style.display = 'flex';
        }

        // Collapse sidebar function
        function collapseSidebar() {
            sidebar.classList.remove('w-64');
            sidebar.classList.add('w-20');

            // Hide header content (school name)
            const headerContent = document.getElementById('sidebar-header-content');
            const menuItems = document.querySelectorAll('.sidebar-menu-item span');
            const categoryLabels = document.querySelectorAll('.sidebar-category-label');
            const footerContent = document.getElementById('sidebar-footer-content');
            const sidebarFooter = document.querySelector('aside > div:last-child');

            if (headerContent) {
                headerContent.classList.add('hidden');
            }

            menuItems.forEach(item => {
                item.classList.add('hidden');
            });

            categoryLabels.forEach(label => {
                label.classList.add('hidden');
            });

            if (footerContent) {
                footerContent.classList.add('hidden');
            }

            if (sidebarFooter) {
                sidebarFooter.classList.add('hidden');
            }

            isCollapsed = true;
            localStorage.setItem('sidebarCollapsed', 'true');
        }

        // Expand sidebar function
        function expandSidebar() {
            sidebar.classList.remove('w-20');
            sidebar.classList.add('w-64');

            // Show header content
            const headerContent = document.getElementById('sidebar-header-content');
            const menuItems = document.querySelectorAll('.sidebar-menu-item span');
            const categoryLabels = document.querySelectorAll('.sidebar-category-label');
            const footerContent = document.getElementById('sidebar-footer-content');
            const sidebarFooter = document.querySelector('aside > div:last-child');

            if (headerContent) {
                headerContent.classList.remove('hidden');
            }

            menuItems.forEach(item => {
                item.classList.remove('hidden');
            });

            categoryLabels.forEach(label => {
                label.classList.remove('hidden');
            });

            if (footerContent) {
                footerContent.classList.remove('hidden');
            }

            if (sidebarFooter) {
                sidebarFooter.classList.remove('hidden');
            }

            isCollapsed = false;
            localStorage.setItem('sidebarCollapsed', 'false');
        }

        // Sidebar toggle button (desktop)
        if (sidebarToggleBtn) {
            sidebarToggleBtn.addEventListener('click', () => {
                if (isCollapsed) {
                    expandSidebar();
                } else {
                    collapseSidebar();
                }
            });
        }

        // Hamburger toggle button (mobile)
        if (hamburgerToggleBtn) {
            hamburgerToggleBtn.addEventListener('click', () => {
                const isVisible = sidebar.style.display === 'flex';
                sidebar.style.display = isVisible ? 'none' : 'flex';
                overlay.classList.toggle('hidden');
            });
        }

        overlay.addEventListener('click', () => {
            sidebar.style.display = 'none';
            overlay.classList.add('hidden');
        });

        // Profile dropdown click handler
        profileDropdownBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = profileDropdownMenu.classList.contains('opacity-100');

            if (isOpen) {
                profileDropdownMenu.classList.remove('opacity-100', 'visible');
                profileDropdownMenu.classList.add('opacity-0', 'invisible');
                profileDropdownArrow.style.transform = 'rotate(0deg)';
            } else {
                profileDropdownMenu.classList.remove('opacity-0', 'invisible');
                profileDropdownMenu.classList.add('opacity-100', 'visible');
                profileDropdownArrow.style.transform = 'rotate(180deg)';
            }
        });

        // Close dropdown when clicking on menu items
        document.querySelectorAll('#profile-dropdown-menu a, #profile-dropdown-menu button').forEach(item => {
            item.addEventListener('click', () => {
                profileDropdownMenu.classList.remove('opacity-100', 'visible');
                profileDropdownMenu.classList.add('opacity-0', 'invisible');
                profileDropdownArrow.style.transform = 'rotate(0deg)';
            });
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!profileDropdownContainer.contains(e.target)) {
                profileDropdownMenu.classList.remove('opacity-100', 'visible');
                profileDropdownMenu.classList.add('opacity-0', 'invisible');
                profileDropdownArrow.style.transform = 'rotate(0deg)';
            }
        });

        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                sidebar.style.display = 'flex';
                overlay.classList.add('hidden');
            } else {
                sidebar.style.display = 'none';
                overlay.classList.add('hidden');
                if (isCollapsed) {
                    expandSidebar();
                }
            }
        });
    </script>
</body>

</html>
