<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Biaya>
 */
class BiayaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement(['SPP', 'PRAKTEK', 'UNIFORM', 'BUKU', 'LAINNYA']),
            'nominal' => $this->faker->numberBetween(20000, 200000),
            'status' => $this->faker->boolean(true),
            'keterangan' => '-',
        ];
    }
}
