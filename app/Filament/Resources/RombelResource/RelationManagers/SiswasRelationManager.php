<?php

namespace App\Filament\Resources\RombelResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Siswa;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class SiswasRelationManager extends RelationManager
{
    protected static string $relationship = 'siswas';



    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->multiple()
                    ->preloadRecordSelect()
                    ->recordTitle(function (Siswa $record): string {
                        // dd($record->rombels->pluck('kode'));
                        $idSiswa = $record->name;
                        $rombel = $record->rombels?->pluck('kode') ?? 'Rombel belum diatur';
                        return "{$idSiswa} — {$rombel}";
                    }),
                   
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
