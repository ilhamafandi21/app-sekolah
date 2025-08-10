<?php

namespace Database\Factories;

use App\Models\Day;
use Illuminate\Database\Eloquent\Factories\Factory;

class DayFactory extends Factory
{
    protected $model = Day::class;

    public function definition(): array
    {
        return [
            'nama_hari' => $this->faker->unique()->randomElement([
                'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu',
            ]),
        ];
    }
}
