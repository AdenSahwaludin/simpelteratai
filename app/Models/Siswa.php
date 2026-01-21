<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Siswa extends Model
{
    use HasFactory;

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
        'id_kelas',
        'alamat',
        'id_orang_tua',
    ];

    /**
     * Get the orang tua that owns the siswa.
     */
    public function orangTua(): BelongsTo
    {
        return $this->belongsTo(OrangTua::class, 'id_orang_tua', 'id_orang_tua');
    }

    /**
     * Get the kelas that the siswa belongs to.
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    /**
     * Get the absensi for the siswa.
     */
    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class, 'id_siswa', 'id_siswa');
    }

    /**
     * Get the laporan perkembangan for the siswa.
     */
    public function laporanPerkembangan(): HasMany
    {
        return $this->hasMany(LaporanPerkembangan::class, 'id_siswa', 'id_siswa');
    }

    /**
     * Generate unique ID with format S000001, S000002, etc.
     * Safe from duplicate even when data is deleted.
     */
    public static function generateUniqueId(): string
    {
        $lastId = static::orderByRaw('CAST(SUBSTRING(id_siswa, 2) AS UNSIGNED) DESC')
            ->limit(1)
            ->pluck('id_siswa')
            ->first();

        $nextNumber = $lastId
            ? (int) substr($lastId, 1) + 1
            : 1;

        return 'S'.str_pad((string) $nextNumber, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Get the perilaku for the siswa.
     */
    public function perilaku(): HasMany
    {
        return $this->hasMany(Perilaku::class, 'id_siswa', 'id_siswa');
    }

    /**
     * Get the jadwal for the siswa.
     */
    public function jadwal(): BelongsToMany
    {
        return $this->belongsToMany(
            Jadwal::class,
            'jadwal_siswa',
            'id_siswa',
            'id_jadwal'
        );
    }
}
