<?php

namespace App\Traits;

use App\Models\Siswa;

trait GeneratesNis
{
    public function generateNis(): string
    {
        $tahun = now()->format('Y');

        $lastNis = Siswa::where('nis', 'like', $tahun.'%')
            ->orderBy('nis', 'desc')
            ->value('nis');

        $lastNumber = $lastNis ? (int) substr($lastNis, 4) : 0;
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return $tahun . $newNumber;
    }
}
