<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tingkat>
 */
class TingkatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        
        return [
            'tahun_ajaran_id' => $this->faker->numberBetween(1, 4),   // random 1–4
            'nama_tingkat'      => $this->faker->numberBetween(10, 12),                  // sesuai migrasi
            'keterangan'      => $this->faker->optional()->paragraph(),
        ];
    }
}
