<?php

namespace App\Traits;

class TahunLulus
{
    public static function tahun_lulus()
    {
      return collect(range(date('Y'), 2000))
            ->mapWithKeys(fn ($year) => [$year => $year])
            ->toArray();
    }
}