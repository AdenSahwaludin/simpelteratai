<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Komentar extends Model
{
 protected $table = 'komentar';
 protected $primaryKey = 'id_komentar';
 public $incrementing = false;
 protected $keyType = 'string';

 protected $fillable = [
  'id_komentar',
  'id_orang_tua',
  'komentar',
 ];

 /**
  * Get the orang tua that owns the komentar.
  */
 public function orangTua(): BelongsTo
 {
  return $this->belongsTo(OrangTua::class, 'id_orang_tua', 'id_orang_tua');
 }
}
