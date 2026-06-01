<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');
        $kelasIds = ['A', 'B'];
        
        $statuses = array_merge(
            array_fill(0, 20, 'Aktif'),
            array_fill(0, 3, 'Mengundurkan Diri'),
            array_fill(0, 5, 'Pindah'),
            array_fill(0, 30, 'Alumni')
        );

        foreach ($statuses as $i => $status) {
            $idOrangTua = \App\Models\OrangTua::generateUniqueId();
            \App\Models\OrangTua::create([
                'id_orang_tua' => $idOrangTua,
                'nama' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'no_telpon' => $faker->numerify('08##########'),
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
            ]);

            $kelasTarget = $kelasIds[$i % 2];
            
            $keterangan_status = null;
            if ($status === 'Mengundurkan Diri') {
                $keterangan_status = 'Alasan keluarga / pindah tempat tinggal';
            } elseif ($status === 'Pindah') {
                $keterangan_status = 'Pindah ke TK ' . $faker->city;
            }

            \App\Models\Siswa::create([
                'id_siswa' => \App\Models\Siswa::generateUniqueId(),
                'nama' => $faker->firstName . ' ' . $faker->lastName,
                'jenis_kelamin' => $faker->randomElement(['L', 'P']),
                'tempat_lahir' => $faker->city,
                'tanggal_lahir' => $faker->date('Y-m-d', '-5 years'),
                'id_kelas' => $kelasTarget,
                'status' => $status,
                'keterangan_status' => $keterangan_status,
                'alamat' => $faker->address,
                'id_orang_tua' => $idOrangTua,
            ]);
        }

        // Auto-assign siswa aktif ke absensi pertemuan
        foreach (\App\Models\Jadwal::all() as $jadwal) {
            $jadwal->assignSiswaToPertemuan();
        }
    }
}
