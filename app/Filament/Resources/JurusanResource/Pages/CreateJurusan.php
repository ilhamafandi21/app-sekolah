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
        $namaJurusan = strtoupper($data['nama_jurusan']);

        $cekdata = \App\Models\Jurusan::where('tahun_ajaran_id', $data['tahun_ajaran_id'])
            ->where('tingkat_id', $data['tingkat_id'])
            ->whereRaw('UPPER(nama_jurusan) = ?', [$namaJurusan])
            ->exists();

        if ($cekdata) {

            \Filament\Notifications\Notification::make()
                ->title('Data Duplikat')
                ->body('Kombinasi tahun ajaran, tingkat, dan nama jurusan sudah ada.')
                ->danger()
                ->send();

            $this->halt();

        } else {
            $data['nama_jurusan'] = $namaJurusan;
            $data['kode'] = "{$thn->thn_ajaran}/{$tingkat->nama_tingkat}/{$namaJurusan}";
        }

        return $data;
    }
}
