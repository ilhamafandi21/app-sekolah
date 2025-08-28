<?php

namespace App\Filament\Resources\RombelResource\RelationManagers;

use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\RombelResource;
use Filament\Resources\RelationManagers\RelationManager;

class TransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'transactions';
    protected static ?string $title = 'Transactions';

    protected static ?string $relatedResource = RombelResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Buat Transaction'),
            ]);
    }
}
