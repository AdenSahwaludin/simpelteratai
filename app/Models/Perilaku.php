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
        'catatan_perilaku',
    ];

    /**
     * Get the siswa that owns the perilaku.
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    /**
     * Get the guru that owns the perilaku.
     */
    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }
}
