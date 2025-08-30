<?php

namespace App\Filament\Resources\IndikatornilaiResource\Pages;

use App\Filament\Resources\IndikatornilaiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateIndikatornilai extends CreateRecord
{
    protected static string $resource = IndikatornilaiResource::class;
    protected static ?string $title = 'Buat Indikator Penilaian';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
