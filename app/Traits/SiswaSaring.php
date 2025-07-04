<?php

namespace App\Traits;

class SiswaSaring
{
public static function siswa_saring() {
                        return Siswa::whereDoesntHave('rombels') // Pastikan relasi `rombels()` ada di model Siswa
                            ->orderBy('nama')
                            ->pluck('nama', 'id');
                    }
}



