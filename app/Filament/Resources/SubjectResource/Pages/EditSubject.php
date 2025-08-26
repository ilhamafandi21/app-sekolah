<?php

namespace App\Filament\Resources\SubjectResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\SubjectResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubject extends EditRecord
{
    protected static string $resource = SubjectResource::class;
    protected static ?string $title = 'Mata Pelajaran';


    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
