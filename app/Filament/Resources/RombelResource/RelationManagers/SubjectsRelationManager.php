<?php

namespace App\Filament\Resources\RombelResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\RelationManagers\RelationManager;

class SubjectsRelationManager extends RelationManager
{
    protected static string $relationship = 'subjects';

    protected static function modifyQuery(Builder $query): Builder
    {
        return $query->with([
            'rombelsSubjects.teacher:id,name',
        ]);
    }

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
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('rombelsSubjects.teacher.name')
                    ->label('Pengajar'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->multiple()
                    ->preloadRecordSelect(),
                    
            ])
            ->actions([
                Tables\Actions\DetachAction::make()
                    ->icon('heroicon-o-trash'),
                Tables\Actions\Action::make('tambahTeacher')
                    ->color(fn ($record) => $record->teacher_id ? 'danger' : 'primary')
                    ->label(fn ($record) => $record->teacher_id ? 'Edit/Kosongkan Teacher' : 'Tambah Teacher')
                    ->form([
                         Forms\Components\Select::make('teacher_id')
                            ->options(\App\Models\Teacher::pluck('name', 'id'))
                            ->searchable()      
                    ])
                    ->action(function(array $data, $record){

                        // dd($record->pivot_teacher_id);

                        $record->pivot->update([
                            'teacher_id' => $data['teacher_id'],
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->title('Berhasil')
                            ->body(
                                is_null($data['teacher_id'])
                                    ? 'Guru telah dikosongkan dari mata pelajaran.'
                                    : 'Guru telah ditambahkan/diedit untuk mata pelajaran.'
                            )
                            ->success()
                            ->send();
                    })
                    ->icon('heroicon-o-plus')
                    ->modalHeading(fn ($record) => $record->teacher_id ? 'Edit/Kosongkan Teacher' : 'Tambah teacher')
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
