<?php

namespace Database\Factories;

use App\Models\TahunAjaran;
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
        $tahunAjaranId = TahunAjaran::query()->inRandomOrder()->firstOrFail()->id;
        
        return [
            'tahun_ajaran_id' => $tahunAjaranId,   // random 1–4
            'nama_tingkat'    => $this->faker->unique()->numberBetween(10, 12),                // sesuai migrasi
            'keterangan'      => $this->faker->optional()->paragraph(),
        ];
    }
}
