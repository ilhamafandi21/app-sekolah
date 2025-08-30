<?php

namespace App\Filament\Resources\TahunAjaranResource\Pages;

use Filament\Actions;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Support\Enums\IconPosition;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\TahunAjaranResource;

class ListTahunAjarans extends ListRecords
{
    protected static string $resource = TahunAjaranResource::class;
    protected static ?string $title = 'Daftar Tahun Ajaran';


    protected function getHeaderActions(): array
    {
        return [
                CreateAction::make()
                    ->label('Buat')
                    ->icon('heroicon-o-plus-circle')
                    ->iconPosition('after'),
        ];
    }
}


