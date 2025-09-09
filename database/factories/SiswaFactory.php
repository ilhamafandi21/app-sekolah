<?php

namespace Database\Factories;

use App\Enums\Agama;
use App\Enums\JenisKelamin;
use App\Models\User;
use App\Models\Siswa;
use App\Enums\StatusSiswa;
use App\Traits\GenerateNis;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Siswa>
 */
class SiswaFactory extends Factory
{
    protected $model = Siswa::class;

    // Counter incremental untuk NIS
    protected static int $counter = 1;

    public function definition(): array
    {
        if (static::$counter > 500) {
            throw new \Exception("NIS incremental sudah mencapai batas maksimal 500");
        }

        // Buat user
        $user = User::create([
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
        ]);

        // Berikan role siswa
        $user->assignRole('siswa');

        return [
            'user_id' => $user->id,
            'nis' => $this->generateNis(),
            'name' => $user->name,
            'tempat_lahir' => $this->faker->city(),
            'tanggal_lahir' => $this->faker->date(),
            'alamat' => $this->faker->address(),
            'agama' => $this->faker->randomElement(['Islam','Kristen','Katolik','Hindu','Buddha']),
            'jenis_kelamin' => $this->faker->randomElement(['L','P']),
            'asal_sekolah' => $this->faker->company(),
            'tahun_lulus' => $this->faker->year(),
            'documents' => null,
            'status' => 'aktif',
        ];
    }

    protected function generateNis(): string
    {
        $year = substr(now()->year, -2); // 2 digit terakhir tahun
        $month = str_pad(now()->month, 2, '0', STR_PAD_LEFT); // 2 digit bulan
        $sequence = str_pad(static::$counter++, 5, '0', STR_PAD_LEFT); // 5 digit urutan

        return $year . $month . $sequence;
    }
}
