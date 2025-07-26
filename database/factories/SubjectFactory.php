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
       $mapel = [
            // Wajib Nasional/Umum (SMA & SMK)
            ['kode' => 'IND101', 'name' => 'Bahasa Indonesia'],
            ['kode' => 'ING101', 'name' => 'Bahasa Inggris'],
            ['kode' => 'MTK101', 'name' => 'Matematika'],
            ['kode' => 'PAI101', 'name' => 'Pendidikan Agama Islam'],
            ['kode' => 'PKN101', 'name' => 'Pendidikan Kewarganegaraan'],
            ['kode' => 'SEJ101', 'name' => 'Sejarah Indonesia'],
            ['kode' => 'SEN101', 'name' => 'Seni Budaya'],
            ['kode' => 'OR101',  'name' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan'],
            ['kode' => 'TIK101', 'name' => 'Informatika / TIK'],
            ['kode' => 'PRK101', 'name' => 'Prakarya dan Kewirausahaan'],
            // Muatan Lokal/Keagamaan
            ['kode' => 'AGM101', 'name' => 'Pendidikan Agama (Non Islam)'],
            ['kode' => 'MLK101', 'name' => 'Muatan Lokal'],

            // SMA IPA
            ['kode' => 'BIO101', 'name' => 'Biologi'],
            ['kode' => 'FIS101', 'name' => 'Fisika'],
            ['kode' => 'KIM101', 'name' => 'Kimia'],
            // SMA IPS
            ['kode' => 'EKO101', 'name' => 'Ekonomi'],
            ['kode' => 'GEO101', 'name' => 'Geografi'],
            ['kode' => 'SOS101', 'name' => 'Sosiologi'],
            ['kode' => 'SEJ102', 'name' => 'Sejarah (Peminatan IPS)'],
            // SMA Bahasa
            ['kode' => 'JPN101', 'name' => 'Bahasa Jepang'],
            ['kode' => 'ARB101', 'name' => 'Bahasa Arab'],
            ['kode' => 'JRM101', 'name' => 'Bahasa Jerman'],
            ['kode' => 'FRN101', 'name' => 'Bahasa Prancis'],
            ['kode' => 'KRN101', 'name' => 'Bahasa Korea'],
            ['kode' => 'SNS101', 'name' => 'Sastra Indonesia'],
            ['kode' => 'ANT101', 'name' => 'Antropologi'],

            // SMK (Umum)
            ['kode' => 'SMKM101', 'name' => 'Matematika (SMK)'],
            ['kode' => 'SMKB101', 'name' => 'Bahasa Indonesia (SMK)'],
            ['kode' => 'SMKI101', 'name' => 'Bahasa Inggris (SMK)'],
            ['kode' => 'SMKP101', 'name' => 'Pendidikan Agama (SMK)'],
            ['kode' => 'SMKK101', 'name' => 'Pendidikan Kewarganegaraan (SMK)'],
            ['kode' => 'SMKS101', 'name' => 'Sejarah Indonesia (SMK)'],
            ['kode' => 'SMKJ101', 'name' => 'PJOK (SMK)'],
            ['kode' => 'SMKT101', 'name' => 'Informatika (SMK)'],
            ['kode' => 'SMKR101', 'name' => 'Prakarya & Kewirausahaan (SMK)'],
            ['kode' => 'SMKSB101', 'name' => 'Seni Budaya (SMK)'],

            // SMK Produktif - Jurusan Populer (Contoh)
            // TKJ (Teknik Komputer Jaringan)
            ['kode' => 'TKJ101', 'name' => 'Komputer dan Jaringan Dasar'],
            ['kode' => 'TKJ102', 'name' => 'Pemrograman Dasar'],
            ['kode' => 'TKJ103', 'name' => 'Administrasi Infrastruktur Jaringan'],
            ['kode' => 'TKJ104', 'name' => 'Administrasi Sistem Jaringan'],
            ['kode' => 'TKJ105', 'name' => 'Teknologi Jaringan WAN'],
            // RPL (Rekayasa Perangkat Lunak)
            ['kode' => 'RPL101', 'name' => 'Dasar Pemrograman'],
            ['kode' => 'RPL102', 'name' => 'Pemrograman Web dan Perangkat Bergerak'],
            ['kode' => 'RPL103', 'name' => 'Basis Data'],
            ['kode' => 'RPL104', 'name' => 'Pemrograman Berorientasi Objek'],
            // AKL (Akuntansi)
            ['kode' => 'AKL101', 'name' => 'Akuntansi Dasar'],
            ['kode' => 'AKL102', 'name' => 'Praktek Akuntansi Perusahaan'],
            // OTKP (Perkantoran)
            ['kode' => 'OTKP101', 'name' => 'Korespondensi'],
            ['kode' => 'OTKP102', 'name' => 'Manajemen Perkantoran'],
            // TKR (Otomotif)
            ['kode' => 'TKR101', 'name' => 'Pemeliharaan Mesin Kendaraan Ringan'],
            ['kode' => 'TKR102', 'name' => 'Teknologi Dasar Otomotif'],
            // TSM (Sepeda Motor)
            ['kode' => 'TSM101', 'name' => 'Pemeliharaan Sepeda Motor'],
            ['kode' => 'TSM102', 'name' => 'Dasar Teknik Otomotif'],
            // Farmasi
            ['kode' => 'FAR101', 'name' => 'Dasar-dasar Farmasi'],
            ['kode' => 'FAR102', 'name' => 'Simulasi Digital Farmasi'],
            // Perhotelan
            ['kode' => 'PHL101', 'name' => 'Dasar-dasar Perhotelan'],
            // DKV (Desain Komunikasi Visual)
            ['kode' => 'DKV101', 'name' => 'Dasar-dasar Desain Grafis'],
        ];


        // Ambil satu mata pelajaran unik
        $item = $this->faker->unique()->randomElement($mapel);

        return [
            'kode' => $item['kode'],
            'name' => $item['name'],
            'deskripsi' => $this->faker->optional()->sentence(),
        ];

        // return [
        //     'kode' => 'MP000',
        //     'name' => $this->faker->word(),
        //     'deskripsi' => $this->faker->sentence(),
        // ];
    }
}
