<?php

namespace Database\Factories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubjectFactory extends Factory
{
    protected $model = Subject::class;

    public function definition(): array
    {
        // Mapel umum SMA (wajib nasional)
        $mapel = [
            ['kode' => 'IND101', 'name' => 'Bahasa Indonesia'],
            ['kode' => 'ING101', 'name' => 'Bahasa Inggris'],
            ['kode' => 'MTK101', 'name' => 'Matematika'],
            ['kode' => 'PAI101', 'name' => 'Pendidikan Agama Islam'],     // atau ganti sesuai sekolah
            ['kode' => 'PKN101', 'name' => 'Pendidikan Kewarganegaraan'],
            ['kode' => 'SEJ101', 'name' => 'Sejarah Indonesia'],
            ['kode' => 'SEN101', 'name' => 'Seni Budaya'],
            ['kode' => 'OR101',  'name' => 'PJOK'],
            ['kode' => 'TIK101', 'name' => 'Informatika / TIK'],
            ['kode' => 'PRK101', 'name' => 'Prakarya dan Kewirausahaan'],
            // (opsional) muatan lokal umum
            ['kode' => 'MLK101', 'name' => 'Muatan Lokal'],
        ];

        $item = $this->faker->unique()->randomElement($mapel);

        return [
            'kode'       => $item['kode'],
            'name'       => $item['name'],
            'deskripsi'  => '-',                               // biar ringkas
            'nilai_kkm'  => $this->faker->numberBetween(70, 80), // contoh KKM 70–80
        ];
    }
}
