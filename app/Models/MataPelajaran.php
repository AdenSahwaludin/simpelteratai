<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MataPelajaran extends Model
{
 protected $table = 'mata_pelajaran';
 protected $primaryKey = 'id_mata_pelajaran';
 public $incrementing = false;
 protected $keyType = 'string';

 protected $fillable = [
  'id_mata_pelajaran',
  'nama_mapel',
 ];

 /**
  * Get the jadwal for the mata pelajaran.
  */
 public function jadwal(): HasMany
 {
  return $this->hasMany(Jadwal::class, 'id_mata_pelajaran', 'id_mata_pelajaran');
 }

 /**
  * Get the laporan perkembangan for the mata pelajaran.
  */
 public function laporanPerkembangan(): HasMany
 {
  return $this->hasMany(LaporanPerkembangan::class, 'id_mata_pelajaran', 'id_mata_pelajaran');
 }
}
