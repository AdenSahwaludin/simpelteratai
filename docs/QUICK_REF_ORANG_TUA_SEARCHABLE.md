# Quick Reference - Fitur Searchable Orang Tua & Tambah Cepat

## Endpoints AJAX

### Search Orang Tua

```
GET /admin/siswa-search-orangtua?q={search_term}
```

**Response:**

```json
{
    "results": [{ "id": "OT001", "text": "John Doe (john@email.com)" }],
    "pagination": { "more": false }
}
```

### Store Orang Tua Baru

```
POST /admin/siswa-store-orangtua
```

**Request Body:**

```json
{
    "nama": "John Doe",
    "email": "john@email.com",
    "no_telpon": "08123456789",
    "password": "password123"
}
```

**Success Response:**

```json
{
    "success": true,
    "message": "Data orang tua berhasil ditambahkan",
    "data": {
        "id": "OT001",
        "text": "John Doe (john@email.com)"
    }
}
```

**Error Response (422):**

```json
{
    "message": "The email has already been taken. (and 1 more error)",
    "errors": {
        "email": ["Email sudah terdaftar"],
        "password": ["Password minimal 6 karakter"]
    }
}
```

## JavaScript Functions

### Initialize Select2

```javascript
$("#id_orang_tua").select2({
    ajax: {
        url: "/admin/siswa-search-orangtua",
        dataType: "json",
        delay: 250,
        data: function (params) {
            return { q: params.term };
        },
    },
    placeholder: "-- Pilih atau Cari Orang Tua --",
});
```

### Open Modal

```javascript
$("#btnTambahOrangTua").click(function () {
    $("#modalTambahOrangTua").removeClass("hidden").addClass("flex show");
});
```

### Submit via AJAX

```javascript
$.ajax({
    url: "/admin/siswa-store-orangtua",
    type: "POST",
    data: $("#formTambahOrangTua").serialize(),
    success: function (response) {
        // Add to dropdown
        const newOption = new Option(
            response.data.text,
            response.data.id,
            true,
            true
        );
        $("#id_orang_tua").append(newOption).trigger("change");
    },
});
```

## HTML Structure

### Dropdown dengan Button

```html
<div class="flex gap-2">
    <div class="flex-1">
        <select name="id_orang_tua" id="id_orang_tua"></select>
    </div>
    <button type="button" id="btnTambahOrangTua">
        <i class="fas fa-plus"></i> Tambah Baru
    </button>
</div>
```

### Modal Structure

```html
<div id="modalTambahOrangTua" class="modal fixed inset-0 hidden">
    <div class="modal-content">
        <form id="formTambahOrangTua">
            <input type="text" id="modal_nama" name="nama" />
            <input type="email" id="modal_email" name="email" />
            <input type="text" id="modal_no_telpon" name="no_telpon" />
            <input type="password" id="modal_password" name="password" />
            <button type="submit">Simpan</button>
        </form>
    </div>
</div>
```

## CSS Classes

### Select2 Custom Styles

```css
.select2-container--default .select2-selection--single {
    height: 42px;
    border-radius: 0.5rem;
}
```

### Modal Animation

```css
.modal {
    transition: opacity 0.25s ease;
}
.modal-content {
    transform: translateY(-50px);
    transition: transform 0.3s ease;
}
.modal.show .modal-content {
    transform: translateY(0);
}
```

## Controller Methods

### searchOrangTua

```php
public function searchOrangTua(Request $request): JsonResponse
{
    $search = $request->input('q');

    $orangTua = OrangTua::query()
        ->when($search, function ($query, $search) {
            return $query->where('nama', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        })
        ->orderBy('nama')
        ->limit(20)
        ->get();

    $results = $orangTua->map(function ($item) {
        return [
            'id' => $item->id_orang_tua,
            'text' => "{$item->nama} ({$item->email})",
        ];
    });

    return response()->json([
        'results' => $results,
        'pagination' => ['more' => false],
    ]);
}
```

### storeOrangTua

```php
public function storeOrangTua(Request $request): JsonResponse
{
    $validated = $request->validate([
        'nama' => 'required|string|max:255',
        'email' => 'required|email|unique:orang_tua,email',
        'no_telpon' => 'required|string|max:15',
        'password' => 'required|string|min:6',
    ]);

    $orangTua = new OrangTua;
    $orangTua->id_orang_tua = 'OT'.str_pad((string)(OrangTua::count() + 1), 3, '0', STR_PAD_LEFT);
    $orangTua->nama = $validated['nama'];
    $orangTua->email = $validated['email'];
    $orangTua->no_telpon = $validated['no_telpon'];
    $orangTua->password = Hash::make($validated['password']);
    $orangTua->save();

    return response()->json([
        'success' => true,
        'message' => 'Data orang tua berhasil ditambahkan',
        'data' => [
            'id' => $orangTua->id_orang_tua,
            'text' => "{$orangTua->nama} ({$orangTua->email})",
        ],
    ]);
}
```

## Required Dependencies

### NPM Packages

```bash
npm install select2 --save
```

### CDN (if not using npm)

```html
<!-- CSS -->
<link
    href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
    rel="stylesheet"
/>

<!-- JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
```

## Important Notes

1. **CSRF Token**: Pastikan ada meta tag CSRF di layout

    ```html
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    ```

2. **jQuery Setup**: Setup CSRF untuk semua AJAX request

    ```javascript
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    ```

3. **Password Hashing**: Jangan lupa hash password

    ```php
    'password' => Hash::make($validated['password'])
    ```

4. **ID Generation**: Auto-increment dengan format
    ```php
    'OT' . str_pad((string)(OrangTua::count() + 1), 3, '0', STR_PAD_LEFT)
    // Result: OT001, OT002, OT003, ...
    ```

## Common Issues & Solutions

### Issue: Select2 tidak muncul

**Solution**: Pastikan jQuery dimuat sebelum Select2

```html
<script src="jquery.js"></script>
<!-- Must be first -->
<script src="select2.js"></script>
<!-- After jQuery -->
```

### Issue: AJAX 419 (CSRF Token Mismatch)

**Solution**: Tambahkan meta tag dan setup AJAX

```javascript
$.ajaxSetup({
    headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
});
```

### Issue: Orang tua tidak ter-select otomatis

**Solution**: Pastikan append option dan trigger change

```javascript
$("#id_orang_tua").append(newOption).trigger("change");
```

### Issue: Modal tidak close

**Solution**: Tambahkan event handler untuk close

```javascript
$("#btnCloseModal, #btnBatalModal").click(closeModal);
modal.click(function (e) {
    if (e.target === this) closeModal();
});
```
