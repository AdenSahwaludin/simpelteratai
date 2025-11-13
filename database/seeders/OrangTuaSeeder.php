<?php

namespace Database\Seeders;

use App\Models\OrangTua;
use Illuminate\Database\Seeder;

class OrangTuaSeeder extends Seeder
{
 /**
  * Run the database seeds.
  */
 public function run(): void
 {
  OrangTua::create([
   'id_orang_tua' => 'ORT1',
   'nama' => 'Orang Tua Pertama',
   'email' => 'orangtua@tkteratai.com',
   'password' => 'password123',
   'no_telpon' => '082234567894',
  ]);

  OrangTua::create([
   'id_orang_tua' => 'ORT2',
   'nama' => 'Orang Tua Kedua',
   'email' => 'orangtua2@tkteratai.com',
   'password' => 'password123',
   'no_telpon' => '082234567895',
  ]);
 }
}
