<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalHarian extends Model
{
    protected $table = 'jadwal_harian';

    protected $primaryKey = 'id_jadwal_harian';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_jadwal_harian',
        'tanggal',
        'tema',
        'waktu_mulai',
        'waktu_selesai',
        'kegiatan',
        'catatan',
        'kelas',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu_mulai' => 'datetime:H:i',
        'waktu_selesai' => 'datetime:H:i',
    ];
}
