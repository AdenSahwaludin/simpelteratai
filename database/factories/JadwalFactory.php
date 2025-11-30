<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Jadwal>
 */
class JadwalFactory extends Factory
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

        $ruanganList = ['Kelas A', 'Kelas B', 'Kelas C', 'Ruang Tari', 'Ruang Musik', 'Lapangan'];

        return [
            'id_jadwal' => 'JD'.str_pad((string) $counter, 3, '0', STR_PAD_LEFT),
            'id_guru' => 'G001',
            'id_mata_pelajaran' => 'MP001',
            'ruang' => fake()->randomElement($ruanganList),
            'waktu' => fake()->time('H:i'),
        ];
    }
}
