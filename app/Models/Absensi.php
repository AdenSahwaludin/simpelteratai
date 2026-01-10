<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    /**
     * Get the siswa that owns the absensi.
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    /**
     * Get the pertemuan that owns the absensi.
     */
    public function pertemuan(): BelongsTo
    {
        return $this->belongsTo(Pertemuan::class, 'id_pertemuan', 'id_pertemuan');
    }

    /**
     * Generate unique ID with format A000001, A000002, etc.
     * Safe from duplicate even when data is deleted.
     */
    public static function generateUniqueId(): string
    {
        $lastId = static::orderByRaw('CAST(SUBSTRING(id_absensi, 2) AS UNSIGNED) DESC')
            ->limit(1)
            ->pluck('id_absensi')
            ->first();

        $nextNumber = $lastId
            ? (int) substr($lastId, 1) + 1
            : 1;

        return 'A'.str_pad((string) $nextNumber, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Get the jadwal through pertemuan.
     */
    public function jadwal(): BelongsTo
    {
        return $this->pertemuan->jadwal();
    }

    /**
     * Get the laporan perkembangan for the absensi.
     */
    public function laporanPerkembangan(): HasMany
    {
        return $this->hasMany(LaporanPerkembangan::class, 'id_absensi', 'id_absensi');
    }
}
