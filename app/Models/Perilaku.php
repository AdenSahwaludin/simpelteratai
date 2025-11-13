<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Perilaku extends Model
{
 protected $table = 'perilaku';
 protected $primaryKey = 'id_perilaku';
 public $incrementing = false;
 protected $keyType = 'string';

 protected $fillable = [
  'id_perilaku',
  'id_siswa',
  'catatan_perilaku',
 ];

 /**
  * Get the siswa that owns the perilaku.
  */
 public function siswa(): BelongsTo
 {
  return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
 }
}
