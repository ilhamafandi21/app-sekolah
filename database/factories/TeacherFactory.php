<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::factory()->create();
        return [
            'name' => $user->name, // bisa pakai nama yang sama
            'tgl_lahir' => $this->faker->date(),
            'kota_lahir' => $this->faker->city(),
            'alamat' => $this->faker->address(),
            'pendidikan' => $this->faker->randomElement(['S1', 'S2', 'SMA']),
            'foto' => null,
            'user_id' => $user->id, // hubungkan ke user
        ];
    }
}
