<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Jurusan>
 */
class JurusanFactory extends Factory
{
    public function definition(): array
    {
        // Daftar kode jurusan unik contoh
        $kodeList = [
            // SMA
            ['kode' => 'IPA',      'name' => 'Ilmu Pengetahuan Alam (IPA)'],
            ['kode' => 'IPS',      'name' => 'Ilmu Pengetahuan Sosial (IPS)'],
            ['kode' => 'BAHASA',   'name' => 'Bahasa & Budaya'],
            ['kode' => 'AGAMA',    'name' => 'Agama (Madrasah Aliyah/MA)'],
            ['kode' => 'SENI',     'name' => 'Seni dan Budaya'],

            // SMK Teknologi & Rekayasa
            ['kode' => 'TKJ',   'name' => 'Teknik Komputer dan Jaringan'],
            ['kode' => 'RPL',   'name' => 'Rekayasa Perangkat Lunak'],
            ['kode' => 'DKV',   'name' => 'Desain Komunikasi Visual'],
            ['kode' => 'TEI',   'name' => 'Teknik Elektronika Industri'],
            ['kode' => 'TAV',   'name' => 'Teknik Audio Video'],
            ['kode' => 'TITL',  'name' => 'Teknik Instalasi Tenaga Listrik'],
            ['kode' => 'TPTU',  'name' => 'Teknik Pemanfaatan Tenaga Listrik'],
            ['kode' => 'TP',    'name' => 'Teknik Permesinan'],
            ['kode' => 'TPB',   'name' => 'Teknik Pengelasan'],
            ['kode' => 'TGB',   'name' => 'Teknik Gambar Bangunan'],
            ['kode' => 'TBSM',  'name' => 'Teknik dan Bisnis Sepeda Motor'],
            ['kode' => 'TKR',   'name' => 'Teknik Kendaraan Ringan Otomotif'],
            ['kode' => 'TIT',   'name' => 'Teknik Instrumentasi Logam'],
            ['kode' => 'TPL',   'name' => 'Teknik Pendingin dan Tata Udara'],
            ['kode' => 'TFLM',  'name' => 'Teknik Fabrikasi Logam dan Manufaktur'],
            ['kode' => 'TPPM',  'name' => 'Teknik Pengecoran Logam'],
            ['kode' => 'TGM',   'name' => 'Teknik Geomatika'],
            ['kode' => 'TPT',   'name' => 'Teknik Pembangkit Tenaga Listrik'],
            ['kode' => 'TKA',   'name' => 'Teknik Konstruksi dan Properti'],
            ['kode' => 'TB',    'name' => 'Teknik Bangunan'],
            ['kode' => 'TKBA',  'name' => 'Teknik Konstruksi Batu dan Beton'],
            ['kode' => 'TBT',   'name' => 'Teknik Beton'],
            ['kode' => 'TAP',   'name' => 'Teknik Alat Berat'],
            ['kode' => 'TMM',   'name' => 'Teknik Mekanik Mesin'],
            ['kode' => 'TOI',   'name' => 'Teknik Otomasi Industri'],
            ['kode' => 'TLA',   'name' => 'Teknik Logistik'],
            ['kode' => 'TSP',   'name' => 'Teknik Sepeda Motor'],
            ['kode' => 'TPK',   'name' => 'Teknik Perkapalan'],
            ['kode' => 'TIK',   'name' => 'Teknik Instalasi Kelistrikan'],
            ['kode' => 'TET',   'name' => 'Teknik Elektronika Telekomunikasi'],
            ['kode' => 'TEAV',  'name' => 'Teknik Elektronika Audio Video'],
            ['kode' => 'TEP',   'name' => 'Teknik Elektronika Pembangkit'],
            ['kode' => 'TLP',   'name' => 'Teknik Laboratorium Pengujian'],
            ['kode' => 'TR',    'name' => 'Teknik Refrigerasi'],

            // SMK Bisnis & Manajemen
            ['kode' => 'AKL',   'name' => 'Akuntansi dan Keuangan Lembaga'],
            ['kode' => 'AP',    'name' => 'Administrasi Perkantoran'],
            ['kode' => 'BDP',   'name' => 'Bisnis Daring dan Pemasaran'],
            ['kode' => 'MPLB',  'name' => 'Manajemen Perkantoran dan Layanan Bisnis'],
            ['kode' => 'OTKP',  'name' => 'Otomatisasi dan Tata Kelola Perkantoran'],
            ['kode' => 'PM',    'name' => 'Perbankan dan Keuangan Mikro'],
            ['kode' => 'PBK',   'name' => 'Perbankan'],
            ['kode' => 'PK',    'name' => 'Perhotelan dan Katering'],
            ['kode' => 'MM',    'name' => 'Multimedia'],
            ['kode' => 'PLB',   'name' => 'Perhotelan dan Layanan Bisnis'],
            ['kode' => 'TL',    'name' => 'Teknik Logistik'],

            // SMK Pariwisata
            ['kode' => 'PH',    'name' => 'Perhotelan'],
            ['kode' => 'UPW',   'name' => 'Usaha Perjalanan Wisata'],
            ['kode' => 'KUL',   'name' => 'Kuliner'],
            ['kode' => 'JWP',   'name' => 'Jasa Boga'],

            // SMK Kesehatan & Pekerjaan Sosial
            ['kode' => 'FAR',   'name' => 'Farmasi Klinis dan Komunitas'],
            ['kode' => 'KFS',   'name' => 'Keperawatan Farmasi dan Sosial'],
            ['kode' => 'KPR',   'name' => 'Keperawatan'],
            ['kode' => 'KTP',   'name' => 'Keperawatan Ternak dan Peternakan'],
            ['kode' => 'ANM',   'name' => 'Asisten Perawatan Medik'],
            ['kode' => 'NUT',   'name' => 'Nutrisi dan Dietetika'],
            ['kode' => 'TEK',   'name' => 'Teknologi Laboratorium Medik'],

            // SMK Seni & Industri Kreatif
            ['kode' => 'SB',    'name' => 'Seni Broadcasting dan Film'],
            ['kode' => 'ANIM',  'name' => 'Animasi'],
            ['kode' => 'KR',    'name' => 'Kriya Kreatif'],
            ['kode' => 'DG',    'name' => 'Desain Grafis'],
            ['kode' => 'DPB',   'name' => 'Desain dan Produksi Busana'],
            ['kode' => 'DKT',   'name' => 'Desain Komunikasi dan Teknologi'],

            // SMK Agribisnis & Agroteknologi
            ['kode' => 'ATP',   'name' => 'Agribisnis Tanaman Pangan dan Hortikultura'],
            ['kode' => 'ATU',   'name' => 'Agribisnis Ternak Unggas'],
            ['kode' => 'ATD',   'name' => 'Agribisnis Ternak Daging dan Perah'],
            ['kode' => 'THP',   'name' => 'Teknologi Hasil Pertanian'],
            ['kode' => 'AHP',   'name' => 'Agribisnis Hasil Perikanan'],
            ['kode' => 'APHP',  'name' => 'Agribisnis Pengolahan Hasil Pertanian'],
            ['kode' => 'ATK',   'name' => 'Agribisnis Tanaman Kelapa Sawit'],
            ['kode' => 'ATIH',  'name' => 'Agribisnis Tanaman Industri dan Hias'],

            // SMK Kemaritiman
            ['kode' => 'NKN',   'name' => 'Nautika Kapal Niaga'],
            ['kode' => 'TPK',   'name' => 'Teknik Perkapalan'],
            ['kode' => 'NKPI',  'name' => 'Nautika Kapal Penangkap Ikan'],
            ['kode' => 'TKPI',  'name' => 'Teknika Kapal Penangkap Ikan'],
            ['kode' => 'NKP',   'name' => 'Nautika Kapal Penumpang'],
            ['kode' => 'TPKN',  'name' => 'Teknika Kapal Niaga'],
            ['kode' => 'TPKP',  'name' => 'Teknika Kapal Penumpang'],
            ['kode' => 'TKPK',  'name' => 'Teknika Kapal Perikanan'],

            // Lain-lain
            ['kode' => 'PS',    'name' => 'Pemasaran'],
            ['kode' => 'DPK',   'name' => 'Desain Produk Kreatif dan Kriya'],
            ['kode' => 'DI',    'name' => 'Desain Interior'],
            ['kode' => 'DB',    'name' => 'Desain Busana'],
            ['kode' => 'PR',    'name' => 'Perpajakan'],
            ['kode' => 'PL',    'name' => 'Perikanan Laut'],
            ['kode' => 'PP',    'name' => 'Perikanan Perairan'],
            ['kode' => 'PPU',   'name' => 'Pengolahan dan Pemasaran Hasil Perikanan'],
            ['kode' => 'PPK',   'name' => 'Pengolahan dan Pengawetan Hasil Kelautan'],
            ['kode' => 'TRK',   'name' => 'Teknik Rekayasa Konstruksi'],
            ['kode' => 'TMD',   'name' => 'Teknik Mekatronika Dasar'],
            ['kode' => 'PMT',   'name' => 'Pengelasan dan Fabrikasi Logam'],
            ['kode' => 'TPRO',  'name' => 'Teknik Proses Produksi'],
            ['kode' => 'TPTO',  'name' => 'Teknik Pengolahan Tanah Organik'],
            ['kode' => 'TPLB',  'name' => 'Teknik Pendingin dan Layanan Bisnis'],
        ];


         $item = $this->faker->unique()->randomElement($kodeList);

        return [
            'kode' => $item['kode'],
            'nama' => $item['name'],
            'deskripsi' => $this->faker->optional()->paragraph(),
        ];
    }
}
