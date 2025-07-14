<?php

namespace App\Filament\Resources\RombelResource\Pages;

use App\Filament\Resources\RombelResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRombel extends CreateRecord
{
    protected static string $resource = RombelResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // dd($data);
        $semestertahun = \App\Models\Semester::with('tahun_ajaran')->find($data['semester_id']);
        $tahun = $semestertahun?->tahun_ajaran?->thn_ajaran;
        $semester =  $data['semester_id'];
        $tingkat = $data['tingkat_id'];
        $jurusan = \App\Models\Jurusan::find($data['jurusan_id'])?->kode ?? 'UNV';
        $divisi = $data['divisi'] ?-> $data['divisi'] ?? '1';
        $data['name'] = "{$tahun}/{$semester}/{$tingkat}/{$jurusan}/{$divisi}";
        return $data;
    }
}
