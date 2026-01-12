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
            'id_orang_tua' => 'O000001',
            'nama' => 'Edward',
            'email' => 'edward@tkteratai.com',
            'password' => 'password',
            'no_telpon' => '082234567894',
        ]);

        OrangTua::create([
            'id_orang_tua' => 'O000002',
            'nama' => 'Aden',
            'email' => 'aden@tkteratai.com',
            'password' => 'password',
            'no_telpon' => '082234567895',
        ]);
    }
}
