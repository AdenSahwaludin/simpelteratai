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
   'id_admin' => 'A02',
   'nama' => 'Administrator',
   'email' => 'admin2@tkteratai.com',
   'password' => 'password',
   'no_telpon' => '082234567890',
  ]);

  Admin::create([
   'id_admin' => 'A03',
   'nama' => 'Admin Tiga',
   'email' => 'admin3@tkteratai.com',
   'password' => 'password',
   'no_telpon' => '082234567891',
  ]);
 }
}
