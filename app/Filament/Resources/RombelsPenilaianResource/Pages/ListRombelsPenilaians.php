<?php

namespace App\Filament\Resources\RombelsPenilaianResource\Pages;

use App\Filament\Resources\RombelsPenilaianResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRombelsPenilaians extends ListRecords
{
    protected static string $resource = RombelsPenilaianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
