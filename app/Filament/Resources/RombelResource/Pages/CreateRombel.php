<?php

namespace App\Filament\Resources\RombelResource\Pages;

use App\Filament\Resources\RombelResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRombel extends CreateRecord
{
    protected static string $resource = RombelResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $tahun = \App\Models\TahunAjaran::find($data['tahun_ajaran_id'])?->thn_ajaran;
        $tingkat = $data['tingkat_id'];
        $jurusan = \App\Models\Jurusan::find($data['jurusan_id'])?->kode ?? 'UNV';
        $divisi = $data['divisi'] ?-> $data['divisi'] ?? '1';

        // $count = \App\Models\Rombel::where('tahun_ajaran_id', $data['tahun_ajaran_id'])
        //     ->where('tingkat_id', $data['tingkat_id'])
        //     ->where('jurusan_id', $data['jurusan_id'])
        //     ->count() + 1;

        // $data['name'] = "{$tahun}/{$tingkat}/{$jurusan}/{$count}";
        // dd($tahun.'/'.$tingkat.'/'.$jurusan.'/'.$divisi);
        $data['name'] = "{$tahun}/{$tingkat}/{$jurusan}/{$divisi}";
        dd($data);
        return $data;
    }
}
