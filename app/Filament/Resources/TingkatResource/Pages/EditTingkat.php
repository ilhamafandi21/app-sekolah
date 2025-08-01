<?php

namespace App\Filament\Resources\TingkatResource\Pages;

use App\Filament\Resources\TingkatResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTingkat extends EditRecord
{
    protected static string $resource = TingkatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array 
    {  
        $record = $this->record;

        $original = $record->tahun_ajaran_id == $data['tahun_ajaran_id'] &&
                    $record->tahun_ajaran_id == $data['nama_tingkat'];

        if ($original) {
            return $data;
        }

        $cekdataexists = \App\Models\Tingkat::where('tahun_ajaran_id', $data['tahun_ajaran_id'] )
            ->where('nama_tingkat', $data['nama_tingkat'])
            ->where('id', '!=' ,$record->id)
            ->exists();

        if ($cekdataexists) {

            \Filament\Notifications\Notification::make()
                ->title('Peringatan')
                ->body('Kombinasi tahun ajaran dan tingkat sudah ada.')
                ->danger()
                ->send();

            $this->halt();

        } else {
            $data['tahun_ajaran_id'];
            $data['nama_tingkat'];
            $data['keterangan'];

            \Filament\Notifications\Notification::make()
                ->title('Berhasil')
                ->body('Data berhasil di ubah.')
                ->success()
                ->send();
        }

        return $data;
    }
}
