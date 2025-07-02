<?php

namespace Database\Factories;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Siswa>
 */
class SiswaFactory extends Factory
{
    protected $model = Siswa::class;

    public function definition(): array
    {
        // Buat user secara otomatis untuk setiap siswa
        // $user = User::factory()->create();

        $ttl = $this->faker->date('Y-m-d'); // ex: 2005-12-01
        $reverseTtl = date('dmY', strtotime($ttl)); // ex: 01122005

        return [
            'nama' => $this->faker->name(),
            'tempat_lahir' => $this->faker->city(),
            'ttl' => $ttl,
            'jenis_kelamin' => $this->faker->randomElement(['L', 'P']),
            'alamat' => $this->faker->address(),
            'email' => $this->faker->unique()->safeEmail(),
            'agama' => $this->faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha']),
            'nis' => $this->faker->unique()->numerify('########'),
            'nisn' => $this->faker->unique()->numerify('##########'),
            'asal_sekolah' => $this->faker->company(),
            'status_pendaftaran' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'status_siswa' => $this->faker->randomElement(['aktif', 'nonaktif', 'alumni']),
            'waktu_pendaftaran' => now()->subDays(rand(10, 60))->toDateTimeString(),
            'waktu_siswa_aktif' => now()->toDateTimeString(),
            // 'password' => Hash::make('password'), // bisa disamakan dengan user kalau mau
            'password' => Hash::make($reverseTtl),
            'role' => 'siswa',
            'user_id' => rand(1, 2),
        ];
    }
}
