<?php

namespace App\Traits;

use App\Models\Siswa;
use App\Models\TahunAjaran;          // tabel tahun_ajarans
use Carbon\Carbon;

Trait GenerateNis
{
    public static function next(): string
    {
        // 1) ambil tahun ajaran aktif (ubah query sesuai skema kamu)
        $ta = TahunAjaran::where('aktif', 1)->firstOrFail();   // kolom 'aktif' boolean
        [$awal, $akhir] = explode('-', $ta->nama);             // '2022-2023'

        // 2) bentuk 4-digit prefix
        $firstDigit   = substr($awal, 0, 1);                   // 2
        $lastTwo      = substr($akhir, -2);                    // 23
        $monthDigit   = substr(Carbon::now()->format('m'), -1);// 7
        $prefix       = $firstDigit . $lastTwo . $monthDigit;  // 2237

        // 3) cari urutan terakhir untuk prefix ini
        $lastNis = Siswa::where('nis', 'like', $prefix . '%')
                        ->orderBy('nis', 'desc')
                        ->value('nis');

        $nextSeq = $lastNis
            ? ((int) substr($lastNis, -4)) + 1                 // increment
            : 1;                                               // mulai 0001

        if ($nextSeq > 9998) {                                 // penuh
            throw new \RuntimeException('NIS untuk tahun ajaran ini sudah penuh.');
        }

        return $prefix . str_pad($nextSeq, 4, '0', STR_PAD_LEFT);
    }
}