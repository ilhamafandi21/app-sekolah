<?php

namespace App\Filament\Resources\IndikatornilaiResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\IndikatornilaiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIndikatornilais extends ListRecords
{
    protected static string $resource = IndikatornilaiResource::class;
    protected static ?string $title = 'Daftar Indikator Penilaian';


    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Buat')
                ->icon('heroicon-o-plus-circle')
                ->iconPosition('after'),
        ];
    }
}
