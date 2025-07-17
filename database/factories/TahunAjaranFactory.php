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
    
            $start = $this->faker->unique()->numberBetween(2020, 2027);
            $end = $start + 1;

        return [
            'thn_ajaran' => "{$start}-{$end}",
            'status' => $this->faker->boolean(),
            'keterangan' => $this->faker->sentence(),
            ];
    }
}
