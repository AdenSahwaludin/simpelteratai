<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
 /**
  * Run the database seeds.
  */
 public function run(): void
 {
  Admin::create([
   'id_admin' => 'ADM',
   'nama' => 'Administrator',
   'email' => 'admin@tkteratai.com',
   'password' => 'password123',
   'no_telpon' => '082234567890',
  ]);

  Admin::create([
   'id_admin' => 'AD2',
   'nama' => 'Admin Dua',
   'email' => 'admin2@tkteratai.com',
   'password' => 'password123',
   'no_telpon' => '082234567891',
  ]);
 }
}
