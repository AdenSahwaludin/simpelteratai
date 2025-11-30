# Mencegah View Tanpa Sidebar Menu

## Masalah yang Terjadi

Pada route `http://localhost:8000/admin/jadwal-harian`, menu sidebar tidak muncul karena view tidak memiliki `@section('sidebar-menu')`.

## Solusi yang Diterapkan

### 1. Perbaikan Langsung

✅ Menambahkan `@section('sidebar-menu')` ke semua view jadwal-harian:

-   `resources/views/admin/jadwal-harian/index.blade.php`
-   `resources/views/admin/jadwal-harian/create.blade.php`
-   `resources/views/admin/jadwal-harian/edit.blade.php`

### 2. Dokumentasi Best Practice

✅ Dibuat file `docs/BLADE_VIEW_TEMPLATE.md` dengan:

-   Template standard untuk setiap role
-   Checklist sebelum commit
-   Contoh lengkap per role

### 3. VS Code Snippets

✅ Dibuat file `.vscode/blade.code-snippets` dengan shortcuts:

-   `blade-admin` → Template admin lengkap
-   `blade-guru` → Template guru lengkap
-   `blade-orangtua` → Template orang tua lengkap

### 4. Validation Script

✅ Dibuat script `scripts/validate-blade-views.php` untuk otomatis cek semua view

## Cara Menggunakan

### 1. Menggunakan Snippet di VS Code

```
1. Buat file blade baru
2. Ketik: blade-admin (atau blade-guru / blade-orangtua)
3. Tekan Tab
4. Edit title dan content
5. Otomatis lengkap dengan sidebar-menu!
```

### 2. Menjalankan Validation Script

```bash
# Dari root project
php scripts/validate-blade-views.php
```

Script akan memeriksa semua view dan menampilkan:

-   ✅ File yang valid
-   ❌ File yang missing sidebar-menu
-   ⚠️ File dengan warning

### 3. Template Manual

Jika tidak menggunakan snippet, gunakan template ini:

```blade
@extends('layouts.dashboard')

@section('title', 'Your Title')
@section('nav-color', 'bg-blue-600')
@section('sidebar-color', 'bg-blue-600')
@section('dashboard-title', 'Your Title')
@section('user-name', auth('admin')->user()->nama)
@section('user-role', 'Admin')

@section('sidebar-menu')
    <x-sidebar-menu :guard="'admin'" :currentRoute="request()->route()->getName()" />
@endsection

@section('content')
    <!-- Your content -->
@endsection
```

## Checklist Wajib

Setiap kali membuat view blade baru:

-   [ ] Ada `@extends('layouts.dashboard')`
-   [ ] Ada `@section('title')`
-   [ ] Ada `@section('nav-color')` dengan warna yang benar
-   [ ] Ada `@section('sidebar-color')` dengan warna yang benar
-   [ ] Ada `@section('dashboard-title')`
-   [ ] Ada `@section('user-name')` dengan guard yang benar
-   [ ] Ada `@section('user-role')` dengan role yang benar
-   [ ] **Ada `@section('sidebar-menu')` dengan `<x-sidebar-menu>`** ✅
-   [ ] Ada `@section('content')`

## Git Pre-commit Hook (Opsional)

Untuk otomatis validasi sebelum commit, tambahkan di `.git/hooks/pre-commit`:

```bash
#!/bin/sh

echo "Running blade view validation..."
php scripts/validate-blade-views.php

if [ $? -ne 0 ]; then
    echo "❌ Validation failed! Please fix the errors before committing."
    exit 1
fi

echo "✅ All views are valid!"
exit 0
```

Buat executable:

```bash
chmod +x .git/hooks/pre-commit
```

## Warna Per Role

| Role      | Nav Color       | Sidebar Color   | Guard        |
| --------- | --------------- | --------------- | ------------ |
| Admin     | `bg-blue-600`   | `bg-blue-600`   | `'admin'`    |
| Guru      | `bg-green-600`  | `bg-green-600`  | `'guru'`     |
| Orang Tua | `bg-purple-600` | `bg-purple-600` | `'orangtua'` |

## Testing

Setelah membuat view baru, pastikan:

1. ✅ Menu sidebar muncul
2. ✅ Menu aktif ter-highlight
3. ✅ Warna sesuai role
4. ✅ Nama user tampil
5. ✅ Tidak ada error

## Troubleshooting

### Menu tidak muncul?

Pastikan ada `@section('sidebar-menu')` dengan `<x-sidebar-menu>`

### Menu tidak ter-highlight?

Pastikan parameter `:currentRoute="request()->route()->getName()"` sudah ada

### Warna salah?

Periksa `@section('nav-color')` dan `@section('sidebar-color')`

### Guard error?

Periksa `auth('admin')` atau `auth('guru')` atau `auth('orangtua')` sesuai role
