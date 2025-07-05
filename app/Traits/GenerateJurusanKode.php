<?php

namespace App\Traits;

use App\Models\Jurusan;

trait GenerateJurusanKode
{
    public static function kode_jurusan() {
        $prefix = 'J-';
        $startNumber = 101;

        $existingCodes = Jurusan::where('kode', 'like', $prefix . '%')
            ->pluck('kode')
            ->map(fn($kode) => (int) substr($kode, strlen($prefix)))
            ->sort()
            ->values();

        $nextNumber = $startNumber;

        foreach ($existingCodes as $codeNumber) {
            if ($codeNumber === $nextNumber) {
                $nextNumber++;
            } else {
                break;
            }
        }

        return $prefix . $nextNumber;
    }
}