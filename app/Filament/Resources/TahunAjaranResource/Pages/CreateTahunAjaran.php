<?php

namespace App\Filament\Resources\TahunAjaranResource\Pages;



use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\TahunAjaranResource;

class CreateTahunAjaran extends CreateRecord
{
    protected static string $resource = TahunAjaranResource::class;
    protected static ?string $title = 'Buat Tahun Ajaran Baru';
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
