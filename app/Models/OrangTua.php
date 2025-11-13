<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
