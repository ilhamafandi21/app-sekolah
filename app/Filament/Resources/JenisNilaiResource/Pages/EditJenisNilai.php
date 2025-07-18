<?php

namespace App\Filament\Resources\JenisNilaiResource\Pages;

use App\Filament\Resources\JenisNilaiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJenisNilai extends EditRecord
{
    protected static string $resource = JenisNilaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
