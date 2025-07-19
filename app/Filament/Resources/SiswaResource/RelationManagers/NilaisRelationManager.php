<?php

namespace App\Filament\Resources\SiswaResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NilaisRelationManager extends RelationManager
{
    protected static string $relationship = 'nilais';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
    
                Forms\Components\Select::make('name')
                    ->relationship('rombel.siswasRombel.rombel', 'name')
                    ->required(),
                Forms\Components\TextInput::make('teacher_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('skor')
                    ->numeric(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('rombel.name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
