<?php

namespace App\Filament\Resources\SubjectResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\SubjectResource;

class CreateSubject extends CreateRecord
{
    protected static string $resource = SubjectResource::class;
    protected static ?string $title = 'Mata Pelajaran';

    protected function afterCreate(): void
    {
        // Refresh halaman setelah submit
        $this->redirect(self::getResource()::getUrl('create'));
    }
}
