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
        'id_jadwal',
        'tanggal',
        'status_kehadiran',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Get the siswa that owns the absensi.
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    /**
     * Get the jadwal that owns the absensi.
     */
    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class, 'id_jadwal', 'id_jadwal');
    }

    /**
     * Get the laporan perkembangan for the absensi.
     */
    public function laporanPerkembangan(): HasMany
    {
        return $this->hasMany(LaporanPerkembangan::class, 'id_absensi', 'id_absensi');
    }
}
