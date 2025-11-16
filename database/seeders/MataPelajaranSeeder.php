<?php

namespace Database\Seeders;

use App\Models\MataPelajaran;
use Illuminate\Database\Seeder;

class MataPelajaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mataPelajaran = [
            ['id_mata_pelajaran' => 'MP1', 'nama_mapel' => 'Matematika'],
            ['id_mata_pelajaran' => 'MP2', 'nama_mapel' => 'Bahasa Indonesia'],
            ['id_mata_pelajaran' => 'MP3', 'nama_mapel' => 'Bahasa Inggris'],
            ['id_mata_pelajaran' => 'MP4', 'nama_mapel' => 'IPA (Ilmu Pengetahuan Alam)'],
            ['id_mata_pelajaran' => 'MP5', 'nama_mapel' => 'IPS (Ilmu Pengetahuan Sosial)'],
            ['id_mata_pelajaran' => 'MP6', 'nama_mapel' => 'Pendidikan Agama'],
            ['id_mata_pelajaran' => 'MP7', 'nama_mapel' => 'Pendidikan Kewarganegaraan'],
            ['id_mata_pelajaran' => 'MP8', 'nama_mapel' => 'Seni Budaya'],
            ['id_mata_pelajaran' => 'MP9', 'nama_mapel' => 'Pendidikan Jasmani'],
        ];

        foreach ($mataPelajaran as $mapel) {
            MataPelajaran::create($mapel);
        }
    }
}
