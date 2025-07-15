<?php

namespace App\Filament\Resources\RombelResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RombelsSubjectsRelationManager extends RelationManager
{
    protected static string $relationship = 'rombelsSubjects';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                 Forms\Components\Select::make('teachers')
                        ->label('Pengajar')
                        ->relationship('rombelsSubjects_teachers', 'name')
                        ->preload(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('subject.name')
                    ->label('Mata Pelajaran'),
                Tables\Columns\TextColumn::make('rombelsSubjectsTeachers.teacher.name')
                    ->label('Pengajar')
                    ->badge()
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
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
