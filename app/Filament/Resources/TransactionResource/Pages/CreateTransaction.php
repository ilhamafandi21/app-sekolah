<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['kode'] = 'TRX' . 
            $data['rombel_id'] .
            $data['biaya_id'] .
            $data['siswa_id'] .
            $data['tingkat_id'] .
            $data['jurusan_id'] .
            $data['divisi'] . 
            date('YmdHis');
            
        return $data;
    }
}
