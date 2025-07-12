<?php

namespace App\Filament\Resources\RombelSubjectTeacherResource\Pages;

use App\Filament\Resources\RombelSubjectTeacherResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRombelSubjectTeacher extends EditRecord
{
    protected static string $resource = RombelSubjectTeacherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
