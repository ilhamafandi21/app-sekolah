<?php

namespace App\Filament\Resources\SiswaResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\SiswaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSiswas extends ListRecords
{
    protected static string $resource = SiswaResource::class;
    protected static ?string $title = 'Daftar Siswa';

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
