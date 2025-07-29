<?php

namespace App\Filament\Resources\RombelsSubjectsResource\RelationManagers;

use App\Enums\SemesterEnum;
use App\Models\RombelsSubjects;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RombelsSubjectsTeachersRelationManager extends RelationManager
{
    protected static string $relationship = 'rombelsSubjects_teachers';
    protected static ?string $inverseRelationship = 'rombelsSubjects_teachers';

     public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'semester:id,name,tahun_ajaran_id',
                'teachers:id,name',
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->form(fn (Tables\Actions\AttachAction $action) => [
                        $action->getRecordSelect()
                            ->label('Mata Pelajaran')
                            ->preload()
                            ->searchable(),

                        Forms\Components\TextInput::make('semester_id')
                            ->label('Semester')
                            ->disabled()
                            ->dehydrated(true)
                            ->default(fn () => SemesterEnum::tryFrom($this->getOwnerRecord()?->semester_id)->label()),
                    ])
                    ->recordTitleAttribute('name'),
            ])

            ->actions([
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
