<?php

namespace Database\Factories;

use App\Enums\Agama;
use App\Enums\JenisKelamin;
use App\Models\User;
use App\Models\Siswa;
use App\Enums\StatusSiswa;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'agama'          => $this->faker->randomElement(Agama::cases()),
            'jenis_kelamin'  => $this->faker->randomElement(JenisKelamin::cases()),
            'asal_sekolah'   => $this->faker->company,
            'tahun_lulus'    => $this->faker->year,
            'documents'      => null,
            'status'         => $this->faker->randomElement(StatusSiswa::cases()),
            // Buatkan user baru sekaligus
            'user_id'        => User::factory(),
        ];
    }
}
