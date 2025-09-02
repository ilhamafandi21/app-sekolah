<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransaction extends EditRecord
{
    protected static string $resource = TransactionResource::class;
    protected static ?string $title = 'Edit Pembayaran';

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
