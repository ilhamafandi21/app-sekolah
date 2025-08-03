<?php

namespace App\Filament\Resources\TahunAjaranResource\Pages;

use App\Filament\Resources\TahunAjaranResource;
use Filament\Actions;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\ListRecords;

class ListTahunAjarans extends ListRecords
{
    protected static string $resource = TahunAjaranResource::class;
    protected static ?string $title = 'Data Tahun Ajaran';


    protected function getHeaderActions(): array
    {
        return [
                Actions\CreateAction::make(),
        ];
    }
}
