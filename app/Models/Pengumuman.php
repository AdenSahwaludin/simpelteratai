<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengumuman extends Model
{
    protected $table = 'pengumuman';

    protected $primaryKey = 'id_pengumuman';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_pengumuman',
        'judul',
        'isi',
        'tanggal',
        'id_admin',
        'publikasi',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'publikasi' => 'boolean',
    ];

    /**
     * Generate unique ID with format P00001, P00002, etc.
     * Safe from duplicate even when data is deleted.
     * varchar(6): P + 5 digits = 6 characters
     */
    public static function generateUniqueId(): string
    {
        $lastId = static::orderByRaw('CAST(SUBSTRING(id_pengumuman, 2) AS UNSIGNED) DESC')
            ->limit(1)
            ->pluck('id_pengumuman')
            ->first();

        $nextNumber = $lastId
            ? (int) substr($lastId, 1) + 1
            : 1;

        return 'P'.str_pad((string) $nextNumber, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Get the admin that owns the pengumuman.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'id_admin', 'id_admin');
    }
}
