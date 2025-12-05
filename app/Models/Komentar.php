<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Komentar extends Model
{
    protected $table = 'komentar';

    protected $primaryKey = 'id_komentar';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_komentar',
        'id_orang_tua',
        'id_laporan_lengkap',
        'id_guru',
        'parent_id',
        'komentar',
    ];

    /**
     * Get the orang tua that owns the komentar.
     */
    public function orangTua(): BelongsTo
    {
        return $this->belongsTo(OrangTua::class, 'id_orang_tua', 'id_orang_tua');
    }

    /**
     * Get the guru that owns the komentar.
     */
    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    /**
     * Get the laporan lengkap that owns the komentar.
     */
    public function laporanLengkap(): BelongsTo
    {
        return $this->belongsTo(LaporanLengkap::class, 'id_laporan_lengkap', 'id_laporan_lengkap');
    }

    /**
     * Get the parent comment.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Komentar::class, 'parent_id', 'id_komentar');
    }

    /**
     * Get the replies for the comment.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Komentar::class, 'parent_id', 'id_komentar')->with(['orangTua', 'guru', 'replies']);
    }

    /**
     * Check if comment is from parent.
     */
    public function isFromParent(): bool
    {
        return ! is_null($this->id_orang_tua);
    }

    /**
     * Check if comment is from teacher.
     */
    public function isFromGuru(): bool
    {
        return ! is_null($this->id_guru);
    }
}
