<?php

namespace App\Filament\Resources\JurusanResource\Pages;

use App\Filament\Resources\JurusanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJurusan extends EditRecord
{
    protected static string $resource = JurusanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {

       $record = $this->record;

        $thn = \App\Models\TahunAjaran::find($data['tahun_ajaran_id']);
        $tingkat = \App\Models\Tingkat::find($data['tingkat_id']);
        $namaJurusan = strtoupper($data['nama_jurusan']);

       $original = $record->tahun_ajaran_id == $data['tahun_ajaran_id'] &&
                    $record->tingkat_id == $data['tingkat_id'] &&
                    $record->nama_hurusan == $data['nama_jurusan'];

        if($original){
            return $data;
        }

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
            $data['kode'] = "{$thn->thn_ajaran}/{$tingkat->nama_tingkat}/{$namaJurusan}";
        }

        return $data;
    }
}
