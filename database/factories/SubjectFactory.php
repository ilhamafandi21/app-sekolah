<?php

namespace Database\Factories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subject>
 */
class SubjectFactory extends Factory
{
    protected $model = Subject::class;
    public function definition(): array
    {
        // Daftar mapel umum di SMA (IPA, IPS, Bahasa)
        // $mapel = [
        //     ['kode' => 'MTK101', 'name' => 'Matematika'],
        //     ['kode' => 'BIO101', 'name' => 'Biologi'],
        //     ['kode' => 'FIS101', 'name' => 'Fisika'],
        //     ['kode' => 'KIM101', 'name' => 'Kimia'],
        //     ['kode' => 'ING101', 'name' => 'Bahasa Inggris'],
        //     ['kode' => 'IND101', 'name' => 'Bahasa Indonesia'],
        //     ['kode' => 'EKO101', 'name' => 'Ekonomi'],
        //     ['kode' => 'GEO101', 'name' => 'Geografi'],
        //     ['kode' => 'SEJ101', 'name' => 'Sejarah'],
        //     ['kode' => 'SOS101', 'name' => 'Sosiologi'],
        //     ['kode' => 'SEN101', 'name' => 'Seni Budaya'],
        //     ['kode' => 'OR101',  'name' => 'Pendidikan Jasmani'],
        //     ['kode' => 'PAI101', 'name' => 'Pendidikan Agama Islam'],
        //     ['kode' => 'PKN101', 'name' => 'Pendidikan Kewarganegaraan'],
        //     ['kode' => 'TIK101', 'name' => 'Informatika / TIK'],
        // ];

        // Ambil satu mata pelajaran unik
        // $item = $this->faker->unique()->randomElement($mapel);

        // return [
        //     'kode' => $item['kode'],
        //     'name' => $item['name'],
        //     'deskripsi' => $this->faker->optional()->sentence(),
        // ];

        return [
            'kode' => 'MP000',
            'name' => $this->faker->word(),
            'deskripsi' => $this->faker->sentence(),
        ];
    }
}
