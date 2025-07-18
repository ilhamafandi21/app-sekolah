<?php

namespace App\Filament\Resources\JurusanResource\Pages;

use App\Filament\Resources\JurusanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateJurusan extends CreateRecord
{
    protected static string $resource = JurusanResource::class;

    protected static ?string $title = 'Daftar Jurusan';


    protected function afterCreate(): void
    {
        // Refresh halaman setelah submit
        $this->redirect(self::getResource()::getUrl('index'));
    }
}
