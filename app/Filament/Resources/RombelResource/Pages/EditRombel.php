<?php

namespace App\Filament\Resources\RombelResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\RombelResource;
use Illuminate\Contracts\Support\Htmlable;

class EditRombel extends EditRecord
{
    protected static string $resource = RombelResource::class;
    

    public function getTitle(): string
    {
        return 'Ubah Rombel '.$this->record->kode;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Hapus'),
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
