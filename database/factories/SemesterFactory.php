<?php

namespace Database\Factories;

use App\Models\TahunAjaran;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Semester>
 */
class SemesterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['Ganjil', 'Genap']),
            'status' => $this->faker->boolean(),
            'keterangan' => $this->faker->sentence(),
            'tahun_ajaran_id' => TahunAjaran::factory(), // ← otomatis buat TahunAjaran
        ];
    }
}
