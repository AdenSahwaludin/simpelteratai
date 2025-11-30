<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';

    protected $primaryKey = 'id_jadwal';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_jadwal',
        'id_guru',
        'id_mata_pelajaran',
        'ruang',
        'waktu',
    ];

    protected $casts = [
        'waktu' => 'datetime:H:i',
    ];

    /**
     * Get the guru that owns the jadwal.
     */
    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    /**
     * Get the mata pelajaran that owns the jadwal.
     */
    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class, 'id_mata_pelajaran', 'id_mata_pelajaran');
    }

    /**
     * Get the absensi for the jadwal.
     */
    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class, 'id_jadwal', 'id_jadwal');
    }

    /**
     * Get the siswa for the jadwal.
     */
    public function siswa(): BelongsToMany
    {
        return $this->belongsToMany(
            Siswa::class,
            'jadwal_siswa',
            'id_jadwal',
            'id_siswa'
        )->withTimestamps();
    }
}
