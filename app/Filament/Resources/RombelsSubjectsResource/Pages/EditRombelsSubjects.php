<?php

namespace App\Filament\Resources\RombelsSubjectsResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\RombelsSubjectsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRombelsSubjects extends EditRecord
{
    protected static string $resource = RombelsSubjectsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
