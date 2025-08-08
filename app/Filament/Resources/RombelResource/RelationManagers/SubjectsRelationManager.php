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

    // protected static function modifyQuery(Builder $query): Builder
    // {
    //     return $query->withPivot('teacher_id');
    // }

    protected static function modifyQuery(Builder $query): Builder
    {
        return $query->with([
            'rombelsSubject.teacher'
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
                Tables\Columns\TextColumn::make('RombelsSubjectsSchedullsTeachers.schedull.kode')
                    ->html()     
                    ->getStateUsing(function ($record) {
                            return $record->RombelsSubjectsSchedullsTeachers
                                ->pluck('schedull.kode')
                                ->filter()
                                ->implode('<br>') ?: 'Belum diatur';
                        }),
                Tables\Columns\TextColumn::make('RombelsSubjectsSchedullsTeachers.teacher.name')
                    ->label('Pengajar')
                    ->html()
                    ->getStateUsing(function ($record) {
                        return $record->RombelsSubjectsSchedullsTeachers
                                ->pluck('teacher.name')
                                ->filter()
                                ->implode('<br>') ?: 'Belum diatur';
                    }),
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
                Tables\Actions\Action::make('tambahJadwal')
                    ->label('Atur Jadwal')
                    ->form([
                        Forms\Components\Select::make('schedull_id')
                            ->options(\App\Models\Schedull::pluck('kode', 'id'))
                            ->multiple()
                            ->searchable() 
                    ])
                    ->action(function(array $data, $record){
                        $record->rombelsSubject->update([
                            'schedull_id' => $data['schedull_id'],
                    
                        \Filament\Notifications\Notification::make()
                            ->title('Berhasil')
                            ->body(
                                is_null($data['schedull_id'])
                                    ? 'Jadwal berhasil diatur untuk mata pelajaran.'
                                    : 'Jadwal telah ditambahkan/diedit untuk mata pelajaran.'
                            )
                            ->success()
                            ->send()
                        ]);
                    }),
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

                        $record->rombelsSubject->update([
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
