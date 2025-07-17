<?php

namespace App\Filament\Resources\RombelResource\Pages;

use App\Filament\Resources\RombelResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRombel extends ViewRecord
{
    protected static string $resource = RombelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Ubah Data'),

        ];
    }
}
