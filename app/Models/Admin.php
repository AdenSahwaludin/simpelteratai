<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $table = 'admin';

    protected $primaryKey = 'id_admin';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_admin',
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
     * Get the pengumuman for the admin.
     */
    public function pengumuman(): HasMany
    {
        return $this->hasMany(Pengumuman::class, 'id_admin', 'id_admin');
    }
}
