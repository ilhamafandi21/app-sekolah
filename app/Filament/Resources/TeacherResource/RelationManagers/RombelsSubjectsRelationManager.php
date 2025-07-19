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
    protected static ?string $title = 'Rombel dan Mapel';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('rombel_id')
                    ->relationship('rombel', 'name')
                    ->getOptionLabelFromRecordUsing(fn ($record) =>'Kelas : '. $record->tingkat_id . ' || ' .'Divisi : '. $record->divisi . '   || ' .'Jurusan : '. $record->jurusan->nama)
                    ->required(),
                Forms\Components\Select::make('subject_id')
                    ->unique(ignoreRecord:true)
                    ->validationMessages([
                        'unique' => 'Sudah ada pengajar untuk mapel ini!'
                    ])
                    ->relationship(
                        name: 'subject',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn ($query) => $query->rombelsSubjectsTeachers->whereNull('teacher_id')
                    )
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
