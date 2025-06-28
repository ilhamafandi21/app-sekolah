<?php

namespace App\Observers;

use App\Models\Siswa;
use App\Traits\GeneratesNis;

class SiswaObserver
{
    use GeneratesNis;

    public function creating(Siswa $siswa): void
    {
        if(empty($siswa->nis))
        {
            $siswa->nis = $this->generateNis();
        }
    }
}
