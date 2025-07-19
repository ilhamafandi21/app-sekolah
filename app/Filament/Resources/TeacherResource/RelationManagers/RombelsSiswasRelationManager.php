<?php

namespace App\Filament\Resources\TeacherResource\RelationManagers;

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
    protected static ?string $title = 'Siswa';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('rombel_id')
                    ->relationship('rombel', 'name')
                    ->getOptionLabelFromRecordUsing(fn ($record) =>'Kelas : '. $record->tingkat_id . ' || ' .'Divisi : '. $record->divisi . '   || ' .'Jurusan : '. $record->jurusan->nama)
                    ->required(),
                Forms\Components\Select::make('subject_id')
                    ->relationship('subject', 'name')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('rombel.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subject.name')
                    ->searchable(),

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
