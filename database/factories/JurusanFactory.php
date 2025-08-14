<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Jurusan;
use App\Models\TahunAjaran;
use App\Models\Tingkat;

class JurusanFactory extends Factory
{
    protected $model = Jurusan::class;

    public function definition(): array
    {
        $kodeList = [
            ['kode' => 'IPA',    'name' => 'Ilmu Pengetahuan Alam (IPA)'],
            ['kode' => 'IPS',    'name' => 'Ilmu Pengetahuan Sosial (IPS)'],
            ['kode' => 'BAHASA', 'name' => 'Bahasa & Budaya'],
        ];

        $item = $this->faker->unique()->randomElement($kodeList);

        return [
            'kode'            => $item['kode'],
            'nama_jurusan'    => $item['name'],
            'keterangan'      => '-'
        ];
    }
}
