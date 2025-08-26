<?php

namespace App\Filament\Resources\RombelResource\Pages;

use App\Models\TahunAjaran;
use App\Models\Tingkat;
use App\Models\Jurusan;
use App\Models\Rombel;
use Filament\Actions;
use App\Filament\Resources\RombelResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateRombel extends CreateRecord
{
    protected static string $resource = RombelResource::class;
    protected static ?string $title = 'Buat Rombel Baru';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
      
        $thn =  TahunAjaran::find($data['tahun_ajaran_id'])->id;
        $tingkat = Tingkat::find($data['tingkat_id'])->id;
        $jurusan = Jurusan::find($data['jurusan_id'])->id;
        $div = $data['divisi'];

        $cekdata = Rombel::where('tahun_ajaran_id', $data['tahun_ajaran_id'])
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
            $kode = "{$thn}{$tingkat}{$jurusan}{$div}";
            $data['kode'] = $kode;
        }

        return $data;
    }
}
