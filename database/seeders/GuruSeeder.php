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
            'id_guru' => 'G03',
            'nama' => 'Pak Damar',
            'email' => 'damar@tkteratai.com',
            'password' => 'password',
            'no_telpon' => '082234567892',
        ]);

        Guru::create([
            'id_guru' => 'G04',
            'nama' => 'Ibu Siti',
            'email' => 'siti@tkteratai.com',
            'password' => 'password',
            'no_telpon' => '082234567893',
        ]);
    }
}
