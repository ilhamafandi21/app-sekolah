<?php

namespace App\Filament\Resources\JenisPembayaranResource\Pages;

use App\Filament\Resources\JenisPembayaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;

class ListJenisPembayarans extends ListRecords
{
    protected static string $resource = JenisPembayaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah'),
                // ->color(Color::Gray),
        ];
    }
    protected static ?string $title = 'Kategori Pembayaran'; 
}
