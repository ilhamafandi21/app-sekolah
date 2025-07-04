<?php

namespace App\Traits;

class TahunAjaran
{
    public static function tahun_ajaran(): array
    {
        $tahunMulai = 2023;
        $tahunSekarang = now()->year;
        $tahunMax = $tahunSekarang + 1; // Bisa disesuaikan jika perlu proyeksi ke depan

        $options = [];

        for ($tahun = $tahunMulai; $tahun < $tahunMax; $tahun++) {
            $label = "{$tahun}-" . ($tahun + 1);
            $options[$label] = $label;
        }

        return $options;
    }
}