<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanPerkembangan extends Model
{
 protected $table = 'laporan_perkembangan';
 protected $primaryKey = 'id_laporan';
 public $incrementing = false;
 protected $keyType = 'string';

 protected $fillable = [
  'id_laporan',
  'id_siswa',
  'id_mata_pelajaran',
  'nilai',
  'id_absensi',
  'komentar',
 ];

 protected $casts = [
  'nilai' => 'integer',
 ];

 /**
  * Get the siswa that owns the laporan perkembangan.
  */
 public function siswa(): BelongsTo
 {
  return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
 }

 /**
  * Get the mata pelajaran that owns the laporan perkembangan.
  */
 public function mataPelajaran(): BelongsTo
 {
  return $this->belongsTo(MataPelajaran::class, 'id_mata_pelajaran', 'id_mata_pelajaran');
 }

 /**
  * Get the absensi that owns the laporan perkembangan.
  */
 public function absensi(): BelongsTo
 {
  return $this->belongsTo(Absensi::class, 'id_absensi', 'id_absensi');
 }
}
