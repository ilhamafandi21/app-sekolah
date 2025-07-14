<?php

namespace App\Filament\Resources\RombelResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Subject;
use App\Models\Teacher;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class RombelsSubjectsTeacherRelationManager extends RelationManager
{
    protected static string $relationship = 'RombelsSubjectsTeachers';


    public static function getPluralModelLabel(): string
    {
        return 'Pengampu Mapel'; // 🔁 Label untuk banyak record
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('subject_id')
                    ->label('Mata Pelajaran')
                    ->options(Subject::pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('teacher_id')
                    ->label('Guru Pengampu')
                    ->options(Teacher::pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                Forms\Components\Textarea::make('keterangan')
                    ->label('Keterangan')
                    ->default('-')
                    ->nullable(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('subject.name')
                    ->label('Mata Pelajaran'),
                Tables\Columns\TextColumn::make('teacher.name')
                    ->label('Guru Pengajar'),
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
