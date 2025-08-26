<?php

namespace App\Filament\Resources\BiayaResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\BiayaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBiayas extends ListRecords
{
    protected static string $resource = BiayaResource::class;
    protected static ?string $title = 'Tambah Biaya';


    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Biaya'),
        ];
    }
}
