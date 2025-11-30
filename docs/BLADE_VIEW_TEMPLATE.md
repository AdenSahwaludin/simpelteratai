# Blade View Template - Best Practice

## Standard Template untuk Semua View

Setiap view blade yang menggunakan layout dashboard **WAJIB** memiliki struktur berikut:

### Template Lengkap

```blade
@extends('layouts.dashboard')

@section('title', 'Page Title')
@section('nav-color', 'bg-{color}-600')
@section('sidebar-color', 'bg-{color}-600')
@section('dashboard-title', 'Dashboard Title')
@section('user-name', auth('{guard}')->user()->nama)
@section('user-role', '{Role}')

@section('sidebar-menu')
    <x-sidebar-menu :guard="'{guard}'" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <!-- Your content here -->
@endsection
```

### Warna Per Role

-   **Admin**: `bg-blue-600`
-   **Guru**: `bg-green-600`
-   **Orang Tua**: `bg-purple-600`

### Guards

-   **Admin**: `'admin'`
-   **Guru**: `'guru'`
-   **Orang Tua**: `'orangtua'`

## ⚠️ CRITICAL: Sidebar Menu Section

**JANGAN PERNAH LUPA** menambahkan section ini:

```blade
@section('sidebar-menu')
    <x-sidebar-menu :guard="'admin'" :currentRoute="request()->route()->getName()" />
@endsection
```

Tanpa section ini, menu navigasi tidak akan tampil!

## Checklist Sebelum Commit

-   [ ] Ada `@extends('layouts.dashboard')`
-   [ ] Ada `@section('title')`
-   [ ] Ada `@section('nav-color')`
-   [ ] Ada `@section('sidebar-color')`
-   [ ] Ada `@section('dashboard-title')`
-   [ ] Ada `@section('user-name')`
-   [ ] Ada `@section('user-role')`
-   [ ] **Ada `@section('sidebar-menu')` dengan `<x-sidebar-menu>`** ✅
-   [ ] Ada `@section('content')`

## Contoh Per Role

### Admin View

```blade
@extends('layouts.dashboard')

@section('title', 'Data Siswa')
@section('nav-color', 'bg-blue-600')
@section('sidebar-color', 'bg-blue-600')
@section('dashboard-title', 'Data Siswa')
@section('user-name', auth('admin')->user()->nama)
@section('user-role', 'Admin')

@section('sidebar-menu')
    <x-sidebar-menu :guard="'admin'" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <!-- Content -->
@endsection
```

### Guru View

```blade
@extends('layouts.dashboard')

@section('title', 'Kelas Saya')
@section('nav-color', 'bg-green-600')
@section('sidebar-color', 'bg-green-600')
@section('dashboard-title', 'Kelas Saya')
@section('user-name', auth('guru')->user()->nama)
@section('user-role', 'Guru')

@section('sidebar-menu')
    <x-sidebar-menu :guard="'guru'" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <!-- Content -->
@endsection
```

### Orang Tua View

```blade
@extends('layouts.dashboard')

@section('title', 'Data Anak')
@section('nav-color', 'bg-purple-600')
@section('sidebar-color', 'bg-purple-600')
@section('dashboard-title', 'Data Anak')
@section('user-name', auth('orangtua')->user()->nama)
@section('user-role', 'Orang Tua')

@section('sidebar-menu')
    <x-sidebar-menu :guard="'orangtua'" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <!-- Content -->
@endsection
```

## VS Code Snippet (Opsional)

Tambahkan snippet ini ke `.vscode/blade.code-snippets`:

```json
{
    "Laravel Blade Admin View": {
        "prefix": "blade-admin",
        "body": [
            "@extends('layouts.dashboard')",
            "",
            "@section('title', '${1:Title}')",
            "@section('nav-color', 'bg-blue-600')",
            "@section('sidebar-color', 'bg-blue-600')",
            "@section('dashboard-title', '${1:Title}')",
            "@section('user-name', auth('admin')->user()->nama)",
            "@section('user-role', 'Admin')",
            "",
            "@section('sidebar-menu')",
            "    <x-sidebar-menu :guard=\"'admin'\" :currentRoute=\"request()->route()->getName()\" />",
            "@endsection",
            "",
            "@section('content')",
            "    $0",
            "@endsection"
        ],
        "description": "Admin Blade View Template"
    },
    "Laravel Blade Guru View": {
        "prefix": "blade-guru",
        "body": [
            "@extends('layouts.dashboard')",
            "",
            "@section('title', '${1:Title}')",
            "@section('nav-color', 'bg-green-600')",
            "@section('sidebar-color', 'bg-green-600')",
            "@section('dashboard-title', '${1:Title}')",
            "@section('user-name', auth('guru')->user()->nama)",
            "@section('user-role', 'Guru')",
            "",
            "@section('sidebar-menu')",
            "    <x-sidebar-menu :guard=\"'guru'\" :currentRoute=\"request()->route()->getName()\" />",
            "@endsection",
            "",
            "@section('content')",
            "    $0",
            "@endsection"
        ],
        "description": "Guru Blade View Template"
    },
    "Laravel Blade OrangTua View": {
        "prefix": "blade-orangtua",
        "body": [
            "@extends('layouts.dashboard')",
            "",
            "@section('title', '${1:Title}')",
            "@section('nav-color', 'bg-purple-600')",
            "@section('sidebar-color', 'bg-purple-600')",
            "@section('dashboard-title', '${1:Title}')",
            "@section('user-name', auth('orangtua')->user()->nama)",
            "@section('user-role', 'Orang Tua')",
            "",
            "@section('sidebar-menu')",
            "    <x-sidebar-menu :guard=\"'orangtua'\" :currentRoute=\"request()->route()->getName()\" />",
            "@endsection",
            "",
            "@section('content')",
            "    $0",
            "@endsection"
        ],
        "description": "Orang Tua Blade View Template"
    }
}
```

## Cara Menggunakan Snippet

1. Ketik `blade-admin` untuk admin view
2. Ketik `blade-guru` untuk guru view
3. Ketik `blade-orangtua` untuk orangtua view
4. Tekan Tab
5. Edit title dan isi content

## Testing Checklist

Sebelum push ke repository, pastikan:

1. ✅ Menu sidebar muncul di semua halaman
2. ✅ Route aktif ter-highlight di menu
3. ✅ Warna navbar dan sidebar sesuai role
4. ✅ Nama user dan role tampil di header
5. ✅ Tidak ada error di console browser
