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
 ];

 protected $casts = [
  'tanggal' => 'date',
 ];

 /**
  * Get the admin that owns the pengumuman.
  */
 public function admin(): BelongsTo
 {
  return $this->belongsTo(Admin::class, 'id_admin', 'id_admin');
 }
}
