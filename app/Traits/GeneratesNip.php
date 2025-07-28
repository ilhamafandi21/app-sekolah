<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait GeneratesNip
{
    public static function generateNip(): string
    {
        $prefix = now()->format('ym'); // Contoh: 2507 (tahun 2025 bulan Juli)

        // Cari NIP terakhir yang pakai prefix ini
        $lastNip = static::where('nip', 'like', $prefix . '%')
            ->orderBy('nip', 'desc')
            ->value('nip');

        // Ambil urutan terakhir
        $lastIncrement = $lastNip ? (int)substr($lastNip, -4) : 0;
        $newIncrement = str_pad($lastIncrement + 1, 4, '0', STR_PAD_LEFT);

        return $prefix . $newIncrement;
    }
}
