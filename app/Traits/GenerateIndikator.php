<?php

namespace App\Traits;

use App\Models\Indikatornilai;

trait GenerateIndikator
{
    public static function indikator() {
        $prefix = 'I-';
        $startNumber = 101;

        $existingCodes = Indikatornilai::where('kode', 'like', $prefix . '%')
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