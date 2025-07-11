<?php

namespace App\Traits;

use App\Models\TahunAjaran as TahunAjaranModel;

class TahunAjaran
{
    public static function tahun_ajaran(): array
    {
         return TahunAjaranModel::orderByDesc('thn_ajaran')
            ->pluck('thn_ajaran', 'thn_ajaran') // ['2024-2025' => '2024-2025', ...]
            ->toArray();
    }
}