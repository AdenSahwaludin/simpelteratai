<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MataPelajaran>
 */
class MataPelajaranFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $counter = 0;
        $counter++;

        $mataPelajaran = [
            'Matematika',
            'Bahasa Indonesia',
            'Bahasa Inggris',
            'IPA (Ilmu Pengetahuan Alam)',
            'IPS (Ilmu Pengetahuan Sosial)',
            'Pendidikan Agama',
            'Pendidikan Kewarganegaraan',
            'Seni Budaya',
            'Pendidikan Jasmani',
            'Prakarya',
            'Bahasa Daerah',
            'Matematika Lanjutan',
            'Fisika',
            'Kimia',
            'Biologi',
        ];

        return [
            'id_mata_pelajaran' => 'MP' . str_pad((string)$counter, 1, '0', STR_PAD_LEFT),
            'nama_mapel' => $mataPelajaran[$counter - 1] ?? fake()->words(2, true),
        ];
    }
}
