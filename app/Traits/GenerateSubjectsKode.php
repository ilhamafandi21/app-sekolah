<?php

namespace App\Traits;

use App\Models\Subject;

trait GenerateSubjectsKode
{
    public static function kode_subject() {
       $prefix = 'MP';
        $startNumber = 101;
        $maxNumber = 999;

        $existingCodes = Subject::where('kode', 'like', $prefix . '%')
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

        if ($nextNumber > $maxNumber) {
            return 'FULL999'; // Tanda bahwa sudah penuh
        }

        return $prefix . $nextNumber;
    }
}

