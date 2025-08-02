<?php

namespace App\Filament\Resources\RombelResource\Pages;

use Filament\Actions;
use App\Filament\Resources\RombelResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateRombel extends CreateRecord
{
    protected static string $resource = RombelResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
      
        $thn =  \App\Models\TahunAjaran::find($data['tahun_ajaran_id'])->thn_ajaran;
        $tingkat = \App\Models\Tingkat::find($data['tingkat_id'])->nama_tingkat;
        $jurusan = \App\Models\Jurusan::find($data['jurusan_id'])->nama_jurusan;
        $div = $data['divisi'];

        $cekdata = \App\Models\Rombel::where('tahun_ajaran_id', $data['tahun_ajaran_id'])
                                    ->where('tingkat_id', $data['tingkat_id'])
                                    ->where('jurusan_id', $data['jurusan_id'])
                                    ->where('divisi', $div)
                                    ->exists();
        
        if($cekdata){

            Notification::make()
                ->title('Duplikat Data')
                ->body('Data sudah ada, cek kembali data!')
                ->danger()
                ->send();
            
            $this->halt();

        } else{
            $kode = "{$thn}/{$tingkat}/{$jurusan}/{$div}";
            $data['kode'] = $kode;
        }

        return $data;
    }
}
