<?php

namespace App\Filament\Resources\SemesterResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\SemesterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSemester extends EditRecord
{
    protected static string $resource = SemesterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
