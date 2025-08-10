<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Schedull;

/**
 * @extends Factory<\App\Models\Schedull>
 */
class SchedullFactory extends Factory
{
    protected $model = Schedull::class;

    public function definition(): array
    {
        // Ambil nomor pelajaran unik antara 1–12
        $number = $this->faker->unique()->numberBetween(1, 12);

        // Hitung jam mulai dan selesai berdasarkan nomor pelajaran
        // Misalnya jam pelajaran pertama mulai jam 07:00, tiap pelajaran 45 menit
        $start = now()->startOfDay()->addHours(7)->addMinutes(45 * ($number - 1));
        $end   = (clone $start)->addMinutes(45);

        return [
            'kode'     => 'JP-' . str_pad($number, 2, '0', STR_PAD_LEFT),
            'start_at' => $start->format('H:i'),
            'end_at'   => $end->format('H:i'),
        ];
    }
}
