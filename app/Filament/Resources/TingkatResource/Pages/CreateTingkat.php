<?php

namespace App\Filament\Resources\TingkatResource\Pages;

use App\Filament\Resources\TingkatResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTingkat extends CreateRecord
{
    protected static string $resource = TingkatResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array  
    {  
        $cekdataexists = \App\Models\Tingkat::where('tahun_ajaran_id', $data['tahun_ajaran_id'] )
            ->where('nama_tingkat', $data['nama_tingkat'])
            ->exists();

        if ($cekdataexists) {

            \Filament\Notifications\Notification::make()
                ->title('Data Duplikat')
                ->body('Kombinasi tahun ajaran dan tingkat sudah ada.')
                ->danger()
                ->send();

            $this->halt();

        } else {
            $data['tahun_ajaran_id'];
            $data['nama_tingkat'];
            $data['keterangan'];
        }

        return $data;
    }
}
