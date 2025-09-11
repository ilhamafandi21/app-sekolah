<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use App\Models\Biaya;
use App\Models\Siswa;
use App\Models\SiswaBiaya;
use App\Models\Transaction;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;
    protected static ?string $title = 'Buat Pembayaran Baru';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // dd($data);
        $total = Transaction::where('siswa_id', $data['siswa_id'])
                    ->where('biaya_id', $data['biaya_id'])
                    ->sum('nominal');

        $data['kode'] = $data['rombel_id'] .
            $data['biaya_id'] .
            $data['siswa_id'] .
            $data['tingkat_id'] .
            $data['jurusan_id'] .
            $data['divisi'] .
            date('YmdHis');

            $cekdata = SiswaBiaya::where('siswa_id', $data['siswa_id'])
                                    ->where('biaya_id', $data['biaya_id'])
                                    ->exists();

            if(!$cekdata){
                SiswaBiaya::create([
                    'siswa_id' => $data['siswa_id'],
                    'biaya_id' => $data['biaya_id'],
                    'status' => 0,
                ]);
            }else{
                if(Biaya::find($data('biaya_id'))->nominal == $total + $data['nominal']){
                    SiswaBiaya::where('siswa_id', $data['siswa_id'])
                                ->where('biaya_id', $data['biaya_id'])
                                ->update(['status' => 1]);
                }
            }

        return $data;
    }
}
