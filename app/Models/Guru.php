<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
