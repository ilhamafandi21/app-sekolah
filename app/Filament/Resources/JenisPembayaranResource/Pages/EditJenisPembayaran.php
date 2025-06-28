<?php

namespace App\Filament\Resources\JenisPembayaranResource\Pages;

use App\Filament\Resources\JenisPembayaranResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJenisPembayaran extends EditRecord
{
    protected static string $resource = JenisPembayaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
