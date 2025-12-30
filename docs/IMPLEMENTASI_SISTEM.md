# 5.1.2 Implementasi Sistem

Pada bagian ini menjabarkan terkait baris program pada bagian–bagian inti program.

## 5.1.2.1 Sistem Autentikasi Pengguna

Sistem autentikasi mengimplementasikan fitur login berbasis role untuk tiga tipe pengguna: admin, guru, dan orang tua. Setiap pengguna memiliki kredensial unik berupa email dan password yang dienkripsi menggunakan hashing bcrypt. Sistem ini menggunakan Laravel Sanctum untuk mengelola sesi dan keamanan akses berbasis peran melalui middleware yang telah dikonfigurasi di bootstrap/app.php.

### Sintaks

**File: app/Models/User.php**

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
```

**File: app/Http/Controllers/LoginController.php**

```php
public function store(Request $request): RedirectResponse
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }

    return back()->withErrors([
        'email' => 'Kredensial tidak valid',
    ]);
}
```

---

## 5.1.2.2 Manajemen Data Siswa dan Orang Tua

Fitur manajemen data siswa memungkinkan admin untuk menambah, mengubah, dan menghapus informasi siswa termasuk identitas pribadi, data demografis, dan asosiasi dengan orang tua. Setiap siswa menggunakan custom primary key berupa ID unik string, dengan relasi many-to-one ke orang tua. Sistem ini juga menyimpan informasi kelas dan alamat untuk keperluan administratif sekolah.

### Sintaks

**File: app/Models/Siswa.php**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Siswa extends Model
{
    protected $table = 'siswa';
    protected $primaryKey = 'id_siswa';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_siswa',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'kelas',
        'alamat',
        'id_orang_tua',
    ];

    public function orangTua(): BelongsTo
    {
        return $this->belongsTo(OrangTua::class, 'id_orang_tua', 'id_orang_tua');
    }

    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class, 'id_siswa', 'id_siswa');
    }
}
```

---

## 5.1.2.3 Sistem Jadwal dan Mata Pelajaran

Sistem jadwal mengelola pertemuan mengajar mingguan yang terikat pada guru, mata pelajaran, dan kelas tertentu. Setiap pertemuan mempunyai tanggal, hari, dan jam spesifik yang tercatat dalam database. Fitur ini memudahkan guru melihat jadwal mengajar mereka dan membantu siswa mengetahui jadwal belajar dengan guru-guru mereka sesuai dengan kurikulum sekolah.

### Sintaks

**File: app/Models/Jadwal.php**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jadwal extends Model
{
    protected $table = 'jadwal';
    protected $primaryKey = 'id_jadwal';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_jadwal',
        'id_guru',
        'id_mata_pelajaran',
        'kelas',
        'hari',
        'jam_mulai',
        'jam_selesai',
    ];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class, 'id_mata_pelajaran', 'id_mata_pelajaran');
    }

    public function pertemuan(): HasMany
    {
        return $this->hasMany(Pertemuan::class, 'id_jadwal', 'id_jadwal');
    }
}
```

---

## 5.1.2.4 Sistem Absensi Siswa

Sistem absensi merekam kehadiran siswa dalam setiap pertemuan pembelajaran dengan mencatat status kehadiran (hadir, sakit, izin, atau alpha). Setiap record absensi terhubung dengan data siswa dan pertemuan spesifik. Guru dapat melakukan input absensi secara massal dan mengelola data absensi termasuk fitur bulk delete untuk kemudahan administrasi pembelajaran.

### Sintaks

**File: app/Models/Absensi.php**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absensi extends Model
{
    protected $table = 'absensi';
    protected $primaryKey = 'id_absensi';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_absensi',
        'id_siswa',
        'id_pertemuan',
        'status_kehadiran',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    public function pertemuan(): BelongsTo
    {
        return $this->belongsTo(Pertemuan::class, 'id_pertemuan', 'id_pertemuan');
    }
}
```

---

## 5.1.2.5 Sistem Input Nilai dan Perilaku

Fitur input nilai memungkinkan guru mencatat hasil penilaian siswa untuk mata pelajaran tertentu, sedangkan sistem catatan perilaku merekam observasi moral dan etika siswa selama proses pembelajaran. Kedua data ini terintegrasi dengan laporan perkembangan yang dikirimkan kepada orang tua, memberikan gambaran holistik tentang kemajuan akademik dan personal siswa selama semester berjalan.

### Sintaks

**File: app/Models/LaporanPerkembangan.php**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LaporanPerkembangan extends Model
{
    protected $table = 'laporan_perkembangan';
    protected $primaryKey = 'id_laporan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_laporan',
        'id_siswa',
        'id_guru',
        'nilai_akademik',
        'catatan_perkembangan',
        'tanggal_laporan',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function komentar(): HasMany
    {
        return $this->hasMany(Komentar::class, 'id_laporan', 'id_laporan');
    }
}
```

---

## 5.1.2.6 Sistem Komunikasi Guru-Orang Tua via Komentar

Sistem komunikasi diimplementasikan melalui fitur komentar pada laporan perkembangan, memfasilitasi dialog dua arah antara guru dan orang tua tentang perkembangan anak. Setiap komentar tercatat dengan waktu, pembuat (guru atau orang tua), dan konten deskriptif. Fitur ini mendorong kolaborasi aktif dalam pengasuhan dan pembelajaran siswa tanpa perlu platform komunikasi terpisah.

### Sintaks

**File: app/Models/Komentar.php**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Komentar extends Model
{
    protected $table = 'komentar';
    protected $primaryKey = 'id_komentar';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_komentar',
        'id_laporan',
        'id_user',
        'isi_komentar',
        'created_at',
    ];

    public function laporan(): BelongsTo
    {
        return $this->belongsTo(LaporanPerkembangan::class, 'id_laporan', 'id_laporan');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
```

---

## 5.1.2.7 Dashboard dan Statistik

Dashboard menyajikan informasi ringkas dan visual berupa statistik siswa, jadwal mengajar harian, serta notifikasi penting yang relevan dengan peran pengguna. Admin melihat ringkasan keseluruhan sistem, guru melihat statistik kelas dan jadwal mengajar, sementara orang tua melihat perkembangan anak mereka. Implementasi dashboard menggunakan agregasi data melalui query Eloquent dan template Blade untuk rendering dinamis.

### Sintaks

**File: app/Http/Controllers/Guru/GuruDashboardController.php** (Pseudocode)

```php
public function index(): View
{
    $guru = Auth::user()->guru;

    $totalSiswa = Siswa::whereHas('jadwal', function ($query) {
        $query->where('id_guru', Auth::user()->guru->id_guru);
    })->count();

    $jadwalHariIni = Jadwal::where('id_guru', $guru->id_guru)
        ->where('hari', now()->format('l'))
        ->with('mataPelajaran')
        ->get();

    $laporanTerbaru = LaporanPerkembangan::where('id_guru', $guru->id_guru)
        ->latest()
        ->limit(5)
        ->get();

    return view('guru.dashboard', [
        'totalSiswa' => $totalSiswa,
        'jadwalHariIni' => $jadwalHariIni,
        'laporanTerbaru' => $laporanTerbaru,
    ]);
}
```

---

## 5.1.2.8 Sistem Pengumuman

Fitur pengumuman memungkinkan admin untuk mempublikasikan informasi penting kepada seluruh pengguna sistem (guru dan orang tua). Setiap pengumuman menyimpan judul, isi, dan tanggal publikasi. Notifikasi pengumuman ditampilkan di dashboard pengguna dan dapat diakses kapan saja melalui halaman pengumuman khusus, memastikan semua stakeholder mendapatkan informasi terkini tentang kegiatan dan kebijakan sekolah.

### Sintaks

**File: app/Models/Pengumuman.php**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengumuman extends Model
{
    protected $table = 'pengumuman';
    protected $primaryKey = 'id_pengumuman';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_pengumuman',
        'judul',
        'isi',
        'id_admin',
        'tanggal_publikasi',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'id_admin', 'id_admin');
    }
}
```

---

## 5.1.2.9 Factory dan Seeding Data

Sistem menggunakan Factory dan Seeder dari Laravel untuk menghasilkan data dummy dan melakukan inisialisasi database dengan data awal. Factory mendefinisikan skema data palsu untuk model tertentu (User, Siswa, Guru, dll), sementara Seeder mengeksekusi Factory untuk mengisi tabel database. Pendekatan ini memudahkan testing, demo, dan setup environment pengembangan tanpa input data manual yang memakan waktu.

### Sintaks

**File: database/factories/SiswaFactory.php**

```php
<?php

namespace Database\Factories;

use App\Models\Siswa;
use Illuminate\Database\Eloquent\Factories\Factory;

class SiswaFactory extends Factory
{
    protected $model = Siswa::class;

    public function definition(): array
    {
        return [
            'id_siswa' => 'SIS' . $this->faker->unique()->numerify('######'),
            'nama' => $this->faker->name(),
            'jenis_kelamin' => $this->faker->randomElement(['Laki-laki', 'Perempuan']),
            'tempat_lahir' => $this->faker->city(),
            'tanggal_lahir' => $this->faker->date('Y-m-d', '-5 years'),
            'kelas' => $this->faker->randomElement(['A', 'B', 'C']),
            'alamat' => $this->faker->address(),
            'id_orang_tua' => null,
        ];
    }
}
```

**File: database/seeders/UserSeeder.php**

```php
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Sekolah',
            'email' => 'admin@tkt.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Guru Contoh',
            'email' => 'guru@tkt.com',
            'password' => bcrypt('password'),
            'role' => 'guru',
        ]);

        User::create([
            'name' => 'Orang Tua Contoh',
            'email' => 'orangtua@tkt.com',
            'password' => bcrypt('password'),
            'role' => 'orang_tua',
        ]);
    }
}
```

---
