<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Perilaku extends Model
{
    protected $table = 'perilaku';

    protected $primaryKey = 'id_perilaku';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_perilaku',
        'id_siswa',
        'id_guru',
        'tanggal',
        'sosial',
        'emosional',
        'disiplin',
        'catatan_perilaku',
        'file_lampiran',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Get the siswa that owns the perilaku.
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    /**
     * Generate unique ID with format PR0001, PR0002, etc.
     * Safe from duplicate even when data is deleted.
     * varchar(6): PR + 4 digits = 6 characters
     */
    public static function generateUniqueId(): string
    {
        $lastId = static::orderByRaw('CAST(SUBSTRING(id_perilaku, 3) AS UNSIGNED) DESC')
            ->limit(1)
            ->pluck('id_perilaku')
            ->first();

        $nextNumber = $lastId
            ? (int) substr($lastId, 2) + 1
            : 1;

        return 'PR'.str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get the guru that owns the perilaku.
     */
    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }
}
