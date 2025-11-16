<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Guru;
use App\Models\OrangTua;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
 /**
  * Run the database seeds.
  */
 public function run(): void
 {
  // Create Admin
  Admin::create([
   'id_admin' => 'A01',
   'nama' => 'Admin Simpel Teratai',
   'email' => 'admin@tkteratai.com',
   'password' => Hash::make('password'),
   'no_telpon' => '081234567890',
  ]);

  // Create Guru
  Guru::create([
   'id_guru' => 'G01',
   'nama' => 'Ibu Sarah',
   'email' => 'sarah@simpelteratai.com',
   'password' => Hash::make('password'),
   'no_telpon' => '081234567891',
  ]);

  Guru::create([
   'id_guru' => 'G02',
   'nama' => 'Pak Budi',
   'email' => 'budi@simpelteratai.com',
   'password' => Hash::make('password'),
   'no_telpon' => '081234567892',
  ]);

  // Create Orang Tua
  OrangTua::create([
   'id_orang_tua' => 'O001',
   'nama' => 'Ibu Ani',
   'email' => 'ani@gmail.com',
   'password' => Hash::make('password'),
   'no_telpon' => '081234567893',
  ]);

  OrangTua::create([
   'id_orang_tua' => 'O002',
   'nama' => 'Bapak Ahmad',
   'email' => 'ahmad@gmail.com',
   'password' => Hash::make('password'),
   'no_telpon' => '081234567894',
  ]);
 }
}
