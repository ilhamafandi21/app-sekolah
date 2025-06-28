<?php

namespace App\Filament\Resources\JenisPembayaranResource\Pages;

use App\Filament\Resources\JenisPembayaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJenisPembayarans extends ListRecords
{
    protected static string $resource = JenisPembayaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    protected static ?string $title = 'Kategori Pembayaran'; 
}
