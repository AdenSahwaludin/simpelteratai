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
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'no_telpon' => '081234567890',
        ]);

        // Create Guru
        Guru::create([
            'id_guru' => 'G00001',
            'nip' => '19876543210',
            'nama' => 'Ibu Guru',
            'email' => 'guru@gmail.com',
            'password' => Hash::make('password'),
            'no_telpon' => '081234567891',
        ]);

        Guru::create([
            'id_guru' => 'G00002',
            'nip' => '1987654321',
            'nama' => 'Pak Budi',
            'email' => 'budi@gmail.com',
            'password' => Hash::make('password'),
            'no_telpon' => '081234567892',
        ]);

        // Create Orang Tua
        OrangTua::create([
            'id_orang_tua' => 'OT00003',
            'nama' => 'Ibu Ani',
            'email' => 'orangtua@gmail.com',
            'password' => Hash::make('password'),
            'no_telpon' => '081234567893',
        ]);

        OrangTua::create([
            'id_orang_tua' => 'OT00003',
            'nama' => 'Bapak Ahmad',
            'email' => 'ahmad@gmail.com',
            'password' => Hash::make('password'),
            'no_telpon' => '081234567894',
        ]);
    }
}
