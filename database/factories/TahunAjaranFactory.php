<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TahunAjaran>
 */
class TahunAjaranFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
         return [
            'thn_ajaran' => $this->faker->unique()->randomElement([
                '2023-2024',
            ]),
            'status' => true,
            'keterangan' => $this->faker->sentence(),
        ];
    }
}
