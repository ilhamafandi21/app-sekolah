<?php

namespace App\Filament\Resources\RombelResource\RelationManagers;

use App\Models\Siswa;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\AttachAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Illuminate\Database\Eloquent\Builder;

class SiswasRelationManager extends RelationManager
{
    /** relasi belongsToMany di model Rombel */
    protected static string $relationship = 'siswas';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')

            /* ---------- K O L O M ---------- */
            ->columns([
                TextColumn::make('name')->label('Nama Siswa'),
            ])

            /* ---------- ATTACH ---------- */
            ->headerActions([
                AttachAction::make()
                    ->multiple()
                    ->preloadRecordSelect(),
            ])

            /* ---------- DETACH ( per-record ) ---------- */
            ->recordActions([
                DetachAction::make()
                    ->successNotificationTitle('Berhasil melepas siswa dari rombel'),
            ])

            /* ---------- BULK DETACH ---------- */
            ->bulkActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make()
                        ->action(function ($records, RelationManager $livewire) {
                            $rombel = $livewire->getOwnerRecord();
                            $rombel->siswas()->detach($records->pluck('id'));
                        })
                        ->successNotificationTitle('Semua siswa terlepas'),
                ]),
            ]);
    }
}
