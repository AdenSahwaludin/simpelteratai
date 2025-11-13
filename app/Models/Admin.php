<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
