<?php

namespace App\Filament\Resources\JurusanResource\Pages;

use App\Filament\Resources\JurusanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateJurusan extends CreateRecord
{
    protected static string $resource = JurusanResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $thn = \App\Models\TahunAjaran::find($data['tahun_ajaran_id']);
        $tingkat = \App\Models\Tingkat::find($data['tingkat_id']);
        $jurusan =  $data['nama_jurusan'];

        if ($jurusan) {
            $namaJurusan = strtoupper($jurusan);
        } else {
            // Fallback kalau tidak ditemukan
            $namaJurusan = 'UNKNOWN';
        }

        $data['kode'] = "{$thn->thn_ajaran}/{$tingkat->nama_tingkat}/{$namaJurusan}";

        return $data;
    }
}
