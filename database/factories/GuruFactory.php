<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Guru>
 */
class GuruFactory extends Factory
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

        return [
            'id_guru' => 'G'.str_pad((string) $counter, 3, '0', STR_PAD_LEFT),
            'nip' => fake()->unique()->numerify('####################'),
            'nama' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'no_telpon' => fake()->phoneNumber(),
            'password' => Hash::make('password'),
        ];
    }
}
