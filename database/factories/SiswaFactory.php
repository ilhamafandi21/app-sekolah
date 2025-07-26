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

        // $ttl = $this->faker->date('Y-m-d'); // ex: 2005-12-01
        // $reverseTtl = date('dmY', strtotime($ttl)); // ex: 01122005

        return [
            'name'           => $this->faker->name,
            'tempat_lahir'   => $this->faker->city,
            'tanggal_lahir'  => $this->faker->date(),
            'alamat'         => $this->faker->address,
            'agama'          => $this->faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu']),
            'jenis_kelamin'  => $this->faker->randomElement(['Laki-laki', 'Perempuan']),
            'asal_sekolah'   => $this->faker->company,
            'tahun_lulus'    => $this->faker->year,
            'documents'      => null,
            'status'         => $this->faker->randomElement(['Aktif', 'Lulus', 'Pindah']),
            // Buatkan user baru sekaligus
            'user_id'        => User::factory(),
        ];
    }
}
