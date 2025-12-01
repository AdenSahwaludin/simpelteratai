<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pertemuan extends Model
{
    use HasFactory;

    protected $table = 'pertemuan';

    protected $primaryKey = 'id_pertemuan';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_pertemuan',
        'id_jadwal',
        'pertemuan_ke',
        'tanggal',
        'materi',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'pertemuan_ke' => 'integer',
    ];

    /**
     * Get the jadwal that owns the pertemuan.
     */
    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class, 'id_jadwal', 'id_jadwal');
    }

    /**
     * Get all absensi for this pertemuan.
     */
    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class, 'id_pertemuan', 'id_pertemuan');
    }

    /**
     * Get formatted pertemuan label
     */
    public function getLabelAttribute(): string
    {
        return "Pertemuan {$this->pertemuan_ke} - {$this->tanggal->format('d/m/Y')}";
    }
}
