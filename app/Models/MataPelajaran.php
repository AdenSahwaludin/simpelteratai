<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MataPelajaran extends Model
{
    protected $table = 'mata_pelajaran';

    protected $primaryKey = 'id_mata_pelajaran';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_mata_pelajaran',
        'nama_mapel',
    ];

    /**
     * Generate unique ID with format MP0001, MP0002, etc.
     * Safe from duplicate even when data is deleted.
     * varchar(6): MP + 4 digits = 6 characters
     */
    public static function generateUniqueId(): string
    {
        $lastId = static::orderByRaw('CAST(SUBSTRING(id_mata_pelajaran, 3) AS UNSIGNED) DESC')
            ->limit(1)
            ->pluck('id_mata_pelajaran')
            ->first();

        $nextNumber = $lastId
            ? (int) substr($lastId, 2) + 1
            : 1;

        return 'MP'.str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get the jadwal for the mata pelajaran.
     */
    public function jadwal(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'id_mata_pelajaran', 'id_mata_pelajaran');
    }

    /**
     * Get the laporan perkembangan for the mata pelajaran.
     */
    public function laporanPerkembangan(): HasMany
    {
        return $this->hasMany(LaporanPerkembangan::class, 'id_mata_pelajaran', 'id_mata_pelajaran');
    }
}
