<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    protected $primaryKey = 'id_kelas';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id_kelas',
        'nama_kelas',
        'id_guru_wali',
    ];

    public function siswa(): HasMany
    {
        return $this->hasMany(Siswa::class, 'id_kelas', 'id_kelas');
    }

    public function guruWali(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'id_guru_wali', 'id_guru');
    }
}
