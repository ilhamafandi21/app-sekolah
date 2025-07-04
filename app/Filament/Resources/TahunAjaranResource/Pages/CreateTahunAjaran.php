<?php

namespace App\Filament\Resources\TahunAjaranResource\Pages;

use App\Filament\Resources\TahunAjaranResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTahunAjaran extends CreateRecord
{
    protected static string $resource = TahunAjaranResource::class;
    protected static? string $title = "Buat Tahun Ajaran Baru";

    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     return dd($data); // tidak mengubah apa-apa
    // }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
