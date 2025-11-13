<?php

namespace Database\Seeders;

use App\Models\Guru;
use Illuminate\Database\Seeder;

class GuruSeeder extends Seeder
{
 /**
  * Run the database seeds.
  */
 public function run(): void
 {
  Guru::create([
   'id_guru' => 'GRU',
   'nama' => 'Guru Pertama',
   'email' => 'guru@tkteratai.com',
   'password' => 'password123',
   'no_telpon' => '082234567892',
  ]);

  Guru::create([
   'id_guru' => 'GR2',
   'nama' => 'Guru Kedua',
   'email' => 'guru2@tkteratai.com',
   'password' => 'password123',
   'no_telpon' => '082234567893',
  ]);
 }
}
