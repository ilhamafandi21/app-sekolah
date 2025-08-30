<?php

namespace App\Filament\Resources\TingkatResource\Pages;

use App\Filament\Resources\TingkatResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTingkat extends CreateRecord
{
    protected static string $resource = TingkatResource::class;
    protected static ?string $title = 'Buat Tingkat';
}
