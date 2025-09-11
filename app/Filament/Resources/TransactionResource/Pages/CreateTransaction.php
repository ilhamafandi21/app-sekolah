<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use App\Models\Siswa;
use App\Models\SiswaBiaya;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;
    protected static ?string $title = 'Buat Pembayaran Baru';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        dd($data);
        $data['kode'] = $data['rombel_id'] .
            $data['biaya_id'] .
            $data['siswa_id'] .
            $data['tingkat_id'] .
            $data['jurusan_id'] .
            $data['divisi'] .
            date('YmdHis');

            SiswaBiaya::create([
                'siswa_id' => $data['siswa_id'],
                'biaya_id' => $data['biaya_id'],

            ]);

        return $data;
    }
}
