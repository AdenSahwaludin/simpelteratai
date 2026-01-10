<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Guru extends Authenticatable
{
    use HasFactory;

    protected $table = 'guru';

    protected $primaryKey = 'id_guru';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_guru',
        'nip',
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
     * Get the jadwal for the guru.
     */
    public function jadwal(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'id_guru', 'id_guru');
    }

    /**
     * Generate unique ID with format G00001, G00002, etc.
     * Safe from duplicate even when data is deleted.
     * varchar(6): G + 5 digits = 6 characters
     */
    public static function generateUniqueId(): string
    {
        $lastId = static::orderByRaw('CAST(SUBSTRING(id_guru, 2) AS UNSIGNED) DESC')
            ->limit(1)
            ->pluck('id_guru')
            ->first();

        $nextNumber = $lastId
            ? (int) substr($lastId, 1) + 1
            : 1;

        return 'G'.str_pad((string) $nextNumber, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Get the perilaku for the guru.
     */
    public function perilaku(): HasMany
    {
        return $this->hasMany(Perilaku::class, 'id_guru', 'id_guru');
    }
}
