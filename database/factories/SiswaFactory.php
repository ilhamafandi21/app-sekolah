<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Siswa;
use Illuminate\Support\Str;
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
      $ttl = $this->faker->date('Y-m-d'); // ex: 2006-08-15
        $reverseTtl = date('dmY', strtotime($ttl)); // ex: 15082006

        $name = $this->faker->name();
        $email = $this->faker->unique()->safeEmail();

        // Buat user dan isi otomatis
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'email_verified_at' => now(),
            'password' => Hash::make($reverseTtl),
            'remember_token' => Str::random(10),
        ]);

        // Tambahkan role siswa (jika pakai spatie/permission)
        if (method_exists($user, 'assignRole')) {
            $user->assignRole('siswa');
        }

        return [
            'name' => $name,
            'tempat_lahir' => $this->faker->city(),
            'tanggal_lahir' => $ttl,
            'alamat' => $this->faker->address(),
            'agama' => $this->faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha']),
            'jenis_kelamin' => $this->faker->randomElement(['Laki-laki', 'Perempuan']),
            'asal_sekolah' => $this->faker->company(),
            'tahun_lulus' => (string) $this->faker->numberBetween(2018, 2024),
            'documents' => null,
            'status' => $this->faker->randomElement(['aktif', 'tidak aktif', 'lulus']),
            'user_id' => $user->id,
        ];
    }
}
