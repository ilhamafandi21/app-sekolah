<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
{
    protected $model = Teacher::class;

    // Counter incremental untuk NIP
    protected static int $counter = 1;

    public function definition(): array
    {
        // Buat user terlebih dahulu
        $user = User::create([
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
        ]);

        // Berikan role 'teacher'
        $user->assignRole('teacher');

        return [
            'user_id' => $user->id,
            'nip' => $this->generateNip(),
            'name' => $user->name,
            'tgl_lahir' => $this->faker->date(),
            'kota_lahir' => $this->faker->city(),
            'alamat' => $this->faker->address(),
            'pendidikan' => $this->faker->randomElement(['S1', 'S2', 'S3']),
            'foto' => null,
        ];
    }

    protected function generateNip(): string
    {
        $year = now()->year; // 4 digit tahun
        $monthLastDigit = substr(now()->format('m'), -1); // 1 digit bulan terakhir
        $sequence = str_pad(static::$counter++, 3, '0', STR_PAD_LEFT); // 3 digit urutan

        return $year . $monthLastDigit . $sequence; // Contoh: 20259 + 001 = 20259001
    }
}
