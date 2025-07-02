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
        // Daftar kode jurusan unik contoh
        $kodeList = [
            ['kode' => 'IPA', 'name' => 'IPA'],
            ['kode' => 'IPS', 'name' => 'IPS'],
            ['kode' => 'SASIND', 'name' => 'SASTRA INDONESIA'],
        ];

         $item = $this->faker->unique()->randomElement($kodeList);

        return [
            'kode' => $item['kode'],
            'nama' => $item['name'],
            'deskripsi' => $this->faker->optional()->paragraph(),
        ];
    }
}
