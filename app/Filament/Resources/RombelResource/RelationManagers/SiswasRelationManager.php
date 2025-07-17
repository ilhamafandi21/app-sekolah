<?php

namespace App\Filament\Resources\RombelResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class SiswasRelationManager extends RelationManager
{
    protected static string $relationship = 'siswas';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('no')
                    ->label('No.')
                    ->getStateUsing(function ($record, $livewire, $column) {
                        return ($livewire->getTableRecords()->search(fn ($r) => $r->getKey() === $record->getKey()) + 1);
                }),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('kelas')
                    ->label('Kelas')
                    // ->getStateUsing(function ($record) {
                    //     return "{$record->rombels->first()?->tingkat_id} {$record->jurusan?->nama} {$record->divisi}";
                    // }),
                ->getStateUsing(function ($record) {
                        return $record->rombels
                            ->map(fn ($sr) => $sr->tingkat_id. '-' .Str::limit($sr->jurusan->nama, 5).'-'.$sr->divisi)
                            ->filter() // hilangkan null
                            ->implode(', ') ?: '-';
                    }),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
