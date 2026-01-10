<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class OrangTua extends Authenticatable
{
    protected $table = 'orang_tua';

    protected $primaryKey = 'id_orang_tua';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_orang_tua',
        'nama',
        'password',
        'email',
        'no_telpon',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Get the siswa for the orang tua.
     */
    public function siswa(): HasMany
    {
        return $this->hasMany(Siswa::class, 'id_orang_tua', 'id_orang_tua');
    }

    /**
     * Get the komentar for the orang tua.
     */
    public function komentar(): HasMany
    {
        return $this->hasMany(Komentar::class, 'id_orang_tua', 'id_orang_tua');
    }

    /**
     * Generate unique ID with format OT00001, OT00002, etc.
     * Safe from duplicate even when data is deleted.
     * varchar(7): OT + 5 digits = 7 characters
     */
    public static function generateUniqueId(): string
    {
        $lastId = static::orderByRaw('CAST(SUBSTRING(id_orang_tua, 3) AS UNSIGNED) DESC')
            ->limit(1)
            ->pluck('id_orang_tua')
            ->first();

        $nextNumber = $lastId
            ? (int) substr($lastId, 2) + 1
            : 1;

        return 'OT'.str_pad((string) $nextNumber, 5, '0', STR_PAD_LEFT);
    }
}
