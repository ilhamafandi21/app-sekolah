<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;
    protected static ?string $title = 'Daftar Pembayaran';


    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Buat')
                ->icon('heroicon-o-plus-circle')
                ->iconposition('after'),
        ];
    }
}
