<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Guru extends Authenticatable
{
    protected $table = 'guru';

    protected $primaryKey = 'id_guru';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_guru',
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
     * Get the perilaku for the guru.
     */
    public function perilaku(): HasMany
    {
        return $this->hasMany(Perilaku::class, 'id_guru', 'id_guru');
    }
}
