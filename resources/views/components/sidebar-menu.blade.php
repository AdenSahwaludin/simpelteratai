@props(['guard', 'currentRoute' => ''])

@php
    $roleConfig = match ($guard) {
        'admin' => [
            'hoverBg' => 'hover:bg-blue-50',
            'iconColor' => 'text-blue-600',
            'dashboardRoute' => 'admin.dashboard',
            'menus' => [
                [
                    'category' => 'Data Management',
                    'items' => [
                        [
                            'label' => 'Data Siswa',
                            'icon' => 'fa-users',
                            'color' => 'text-blue-500',
                            'route' => 'admin.siswa.index',
                        ],
                        [
                            'label' => 'Data Guru',
                            'icon' => 'fa-chalkboard-user',
                            'color' => 'text-green-500',
                            'route' => 'admin.guru.index',
                        ],
                        [
                            'label' => 'Data Orang Tua',
                            'icon' => 'fa-people-roof',
                            'color' => 'text-purple-500',
                            'route' => 'admin.orangtua.index',
                        ],
                        [
                            'label' => 'Kelola Jadwal',
                            'icon' => 'fa-calendar-alt',
                            'color' => 'text-teal-500',
                            'route' => 'admin.jadwal.index',
                        ],
                        [
                            'label' => 'Mata Pelajaran',
                            'icon' => 'fa-book',
                            'color' => 'text-orange-500',
                            'route' => 'admin.mata-pelajaran.index',
                        ],
                    ],
                ],
                [
                    'category' => 'Konten',
                    'items' => [
                        [
                            'label' => 'Pengumuman',
                            'icon' => 'fa-bullhorn',
                            'color' => 'text-red-500',
                            'route' => 'admin.pengumuman.index',
                        ],
                    ],
                ],
                [
                    'category' => 'Sistem',
                    'items' => [
                        ['label' => 'Pengguna', 'icon' => 'fa-user-shield', 'color' => 'text-gray-600', 'route' => '#'],
                        [
                            'label' => 'Log Aktivitas',
                            'icon' => 'fa-history',
                            'color' => 'text-gray-500',
                            'route' => '#',
                        ],
                    ],
                ],
            ],
        ],
        'guru' => [
            'hoverBg' => 'hover:bg-green-50',
            'iconColor' => 'text-green-600',
            'dashboardRoute' => 'guru.dashboard',
            'menus' => [
                [
                    'category' => 'Pengajaran',
                    'items' => [
                        [
                            'label' => 'Kelas Saya',
                            'icon' => 'fa-layer-group',
                            'color' => 'text-blue-500',
                            'route' => 'guru.kelas-saya.index',
                        ],
                        [
                            'label' => 'Data Siswa',
                            'icon' => 'fa-users',
                            'color' => 'text-purple-500',
                            'route' => 'guru.siswa.index',
                        ],
                        [
                            'label' => 'Jadwal Mengajar',
                            'icon' => 'fa-calendar',
                            'color' => 'text-orange-500',
                            'route' => 'guru.jadwal.index',
                        ],
                    ],
                ],
                [
                    'category' => 'Input Data Perkembangan',
                    'items' => [
                        [
                            'label' => 'Input Nilai',
                            'icon' => 'fa-pencil-alt',
                            'color' => 'text-green-500',
                            'route' => 'guru.input-nilai.index',
                        ],
                        [
                            'label' => 'Catatan Perilaku',
                            'icon' => 'fa-star',
                            'color' => 'text-yellow-500',
                            'route' => 'guru.catatan-perilaku.index',
                        ],
                        [
                            'label' => 'Kelola Absensi',
                            'icon' => 'fa-calendar-check',
                            'color' => 'text-teal-500',
                            'route' => 'guru.kelola-absensi.index',
                        ],
                    ],
                ],
                [
                    'category' => 'Laporan Ke Orang Tua',
                    'items' => [
                        [
                            'label' => 'Pilih Data Perkembangan',
                            'icon' => 'fa-file-alt',
                            'color' => 'text-indigo-500',
                            'route' => 'guru.laporan-orangtua.index',
                        ],
                    ],
                ],
                [
                    'category' => 'Komunikasi',
                    'items' => [
                        [
                            'label' => 'Pengumuman',
                            'icon' => 'fa-bullhorn',
                            'color' => 'text-red-500',
                            'route' => 'guru.pengumuman.index',
                        ],
                        ['label' => 'Pesan', 'icon' => 'fa-comments', 'color' => 'text-blue-500', 'route' => '#'],
                    ],
                ],
            ],
        ],
        'orangtua' => [
            'hoverBg' => 'hover:bg-purple-100',
            'iconColor' => 'text-purple-600',
            'dashboardRoute' => 'orangtua.dashboard',
            'menus' => [
                [
                    'category' => 'Anak',
                    'items' => [
                        [
                            'label' => 'Data Anak',
                            'icon' => 'fa-child',
                            'color' => 'text-pink-500',
                            'route' => 'orangtua.anak.index',
                        ],
                        [
                            'label' => 'Perkembangan',
                            'icon' => 'fa-chart-line',
                            'color' => 'text-blue-500',
                            'route' => 'orangtua.perkembangan.index',
                        ],
                        [
                            'label' => 'Perilaku',
                            'icon' => 'fa-star',
                            'color' => 'text-yellow-500',
                            'route' => 'orangtua.perilaku.index',
                        ],
                        [
                            'label' => 'Kehadiran',
                            'icon' => 'fa-calendar-check',
                            'color' => 'text-green-500',
                            'route' => 'orangtua.kehadiran.index',
                        ],
                    ],
                ],
                [
                    'category' => 'Komunikasi',
                    'items' => [
                        [
                            'label' => 'Pengumuman',
                            'icon' => 'fa-bullhorn',
                            'color' => 'text-red-500',
                            'route' => 'orangtua.pengumuman.index',
                        ],
                        [
                            'label' => 'Komentar',
                            'icon' => 'fa-comment-dots',
                            'color' => 'text-blue-500',
                            'route' => 'orangtua.komentar.index',
                        ],
                    ],
                ],
            ],
        ],
        default => [
            'hoverBg' => 'hover:bg-gray-50',
            'iconColor' => 'text-gray-600',
            'dashboardRoute' => 'dashboard',
            'menus' => [],
        ],
    };
@endphp

<!-- Dashboard Link -->
<a href="{{ route($roleConfig['dashboardRoute']) }}"
    class="sidebar-menu-item flex items-center gap-3 px-4 py-2 text-gray-700 {{ $roleConfig['hoverBg'] }} rounded-lg transition font-medium {{ $currentRoute === $roleConfig['dashboardRoute'] ? 'bg-gray-100' : '' }}">
    <i class="fas fa-chart-bar {{ $roleConfig['iconColor'] }} icon"></i>
    <span>Dashboard</span>
</a>

<!-- Role-specific Menus -->
@foreach ($roleConfig['menus'] as $section)
    <div class="px-4 py-2">
        <p class="sidebar-category-label text-xs font-semibold text-gray-500 uppercase tracking-wider">
            {{ $section['category'] }}
        </p>
    </div>

    @foreach ($section['items'] as $item)
        <a href="{{ $item['route'] === '#' ? '#' : route($item['route']) }}"
            class="sidebar-menu-item flex items-center gap-3 px-4 py-2 text-gray-700 {{ $roleConfig['hoverBg'] }} rounded-lg transition {{ $currentRoute === $item['route'] ? 'bg-gray-100 font-medium' : '' }}">
            <i class="fas {{ $item['icon'] }} {{ $item['color'] }} icon"></i>
            <span>{{ $item['label'] }}</span>
        </a>
    @endforeach
@endforeach

<!-- Pengaturan Dropdown Section -->
<div class="px-4 py-2 mt-2">
    <p class="sidebar-category-label text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengaturan</p>
</div>

<div x-data="{ open: {{ in_array($currentRoute, ['profile.edit', 'profile.password']) ? 'true' : 'false' }} }">
    <!-- Dropdown Toggle -->
    <button @click="open = !open"
        class="sidebar-menu-item flex items-center justify-between w-full px-4 py-2 text-gray-700 {{ $roleConfig['hoverBg'] }} rounded-lg transition">
        <div class="flex items-center gap-3">
            <i class="fas fa-cog text-gray-600 icon"></i>
            <span>Pengaturan</span>
        </div>
        <i class="fas fa-chevron-down text-gray-500 text-xs transition-transform duration-200"
            :class="{ 'rotate-180': open }"></i>
    </button>

    <!-- Dropdown Items -->
    <div x-show="open" x-collapse class="ml-4 mt-1 space-y-1">
        <a href="{{ route('profile.edit') }}"
            class="sidebar-menu-item flex items-center gap-3 px-4 py-2 text-gray-700 {{ $roleConfig['hoverBg'] }} rounded-lg transition {{ $currentRoute === 'profile.edit' ? 'bg-gray-100 font-medium' : '' }}">
            <i class="fas fa-user-edit text-blue-600 icon text-sm"></i>
            <span class="text-sm">Edit Profil</span>
        </a>

        <a href="{{ route('profile.password') }}"
            class="sidebar-menu-item flex items-center gap-3 px-4 py-2 text-gray-700 {{ $roleConfig['hoverBg'] }} rounded-lg transition {{ $currentRoute === 'profile.password' ? 'bg-gray-100 font-medium' : '' }}">
            <i class="fas fa-lock text-yellow-600 icon text-sm"></i>
            <span class="text-sm">Ubah Password</span>
        </a>
    </div>
</div>
