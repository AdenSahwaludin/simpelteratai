<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Siswa>
 */
class SiswaFactory extends Factory
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

        $kelas = ['A', 'B', 'C'];

        return [
            'id_siswa' => 'S'.str_pad((string) $counter, 3, '0', STR_PAD_LEFT),
            'nama' => fake()->firstName(),
            'kelas' => $kelas[array_rand($kelas)],
            'alamat' => fake()->address(),
        ];
    }
}
