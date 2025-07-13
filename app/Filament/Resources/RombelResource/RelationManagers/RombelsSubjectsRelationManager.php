<?php

namespace App\Filament\Resources\RombelResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class RombelsSubjectsRelationManager extends RelationManager
{
    protected static string $relationship = 'rombelsSubjects';

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
                Tables\Columns\TextColumn::make('rombel.name'),
                Tables\Columns\TextColumn::make('subject.name'),
                Tables\Columns\TextColumn::make('rombel_id'),
                Tables\Columns\TextColumn::make('teachers.name'),
                
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function ($record, $action) {
                        if ($record->teachers()->exists()) {
                            Notification::make()
                                ->title('Gagal Hapus!')
                                ->body('Masih ada guru yg terlibat mata pelajaran ini, silahkan hapus dari sisi Guru dan Mapel')
                                ->danger()
                                ->send();

                            $action->cancel(); 
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
