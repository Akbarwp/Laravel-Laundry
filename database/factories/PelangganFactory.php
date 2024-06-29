<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\odel=Pelanggan>
 */
class PelangganFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->unique()->name(),
            'jenis_kelamin' => fake()->randomElement(['L', 'P']),
            'telepon' => fake()->unique()->e164PhoneNumber(),
            'alamat' => fake()->unique()->address(),
        ];
    }
}
