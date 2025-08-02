<?php

namespace App\Filament\Resources\RombelResource\Pages;

use App\Filament\Resources\RombelResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditRombel extends EditRecord
{
    protected static string $resource = RombelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // dd($data);
        $record = $this->record;

        // $original = $record->tahun_ajaran_id == $data['tahun_ajaran_id'] &&
        //             $record->tingkat_id == $data['tingkat_id'] &&
        //             $record->jurusan_id == $data['jurusan_id'] &&
        //             $record->divisi == $data['divisi'];
        
        // if($original){
        //     return $data;
        // }

        $cekdata = \App\Models\Rombel::where('tahun_ajaran_id', $data['tahun_ajaran_id'])
                                    ->where('tingkat_id', $data['tingkat_id'])
                                    ->where('jurusan_id', $data['jurusan_id'])
                                    ->where('divisi', $data['divisi'])
                                    ->where('id', '!=', $record->id)
                                    ->exists();

        if($cekdata){
            Notification::make()
                ->title('Error')
                ->body('Data seperti ini sudah ada! ')
                ->danger()
                ->send();
                
            $this->halt();
        }

        return $data;
    }
}
