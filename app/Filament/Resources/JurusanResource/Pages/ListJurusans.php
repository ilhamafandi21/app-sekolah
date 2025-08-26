<?php

namespace App\Filament\Resources\JurusanResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\JurusanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJurusans extends ListRecords
{
    protected static string $resource = JurusanResource::class;
    protected static ?string $title = 'Jurusan';


    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Jurusan')
                ->button()
                ->color('success'),
        ];
    }
}
