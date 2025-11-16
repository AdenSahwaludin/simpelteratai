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
        'kelas',
        'alamat',
        'email',
        'no_telpon',
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
     * Get the perilaku for the siswa.
     */
    public function perilaku(): HasMany
    {
        return $this->hasMany(Perilaku::class, 'id_siswa', 'id_siswa');
    }
}
