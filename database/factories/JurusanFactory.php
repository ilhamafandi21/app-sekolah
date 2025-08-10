<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Jurusan>
 */
class JurusanFactory extends Factory
{
    public function definition(): array
    {
        $kodeList = [
            // SMA
            ['kode' => 'IPA',    'name' => 'Ilmu Pengetahuan Alam (IPA)'],
            ['kode' => 'IPS',    'name' => 'Ilmu Pengetahuan Sosial (IPS)'],
            ['kode' => 'BAHASA', 'name' => 'Bahasa & Budaya'],
        ];

        $item = $this->faker->unique()->randomElement($kodeList);

        return [
            'tahun_ajaran_id' => $this->faker->numberBetween(1, 4),   // random 1–4
            'tingkat_id'      => $this->faker->numberBetween(10, 12), // random 10–12
            'kode'            => $item['kode'],
            'nama_jurusan'    => $item['name'],                       // sesuai migrasi
            'keterangan'      => $this->faker->optional()->paragraph(),
        ];
    }
}
