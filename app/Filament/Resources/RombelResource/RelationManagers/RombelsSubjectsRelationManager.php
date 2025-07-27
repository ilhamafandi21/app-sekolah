<?php

namespace App\Filament\Resources\RombelResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Subject;
use App\Models\Semester;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Actions\AttachAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class RombelsSubjectsRelationManager extends RelationManager
{
    protected static string $relationship = 'rombels_subjects';
    protected static ?string $inverseRelationship = 'rombels_subjects'; 

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->allowDuplicates() // jika 1 subject boleh muncul beberapa kali (beda semester)
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('pivot.semester_id')->label('Semester'),
            ])
            ->headerActions([
                AttachAction::make()
                    ->label('Tambah Mata Pelajaran')
                    ->form(fn (AttachAction $action) => [
                        // RECORD SELECT — opsi manual agar tidak kena qualifyColumn() null
                        $action->getRecordSelect()
                            ->label('Mata Pelajaran')
                            ->searchable()
                            ->options(fn () => Subject::query()
                                ->orderBy('name')
                                ->pluck('name', 'id')
                                ->all()
                            ),

                        // pilih / set semester pivot
                        Forms\Components\TextInput::make('semester_id')
                            ->label('Semester')
                            
                            // atau kalau ingin default mengikuti rombel induk:
                            ->default(fn () => $this->getOwnerRecord()->semester_id)
                            ->required(),
                    ])
                    // opsional: atur judul opsi jika perlu
                    ->recordTitleAttribute('name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form([
                        Forms\Components\Select::make('semester_id')
                            ->label('Semester')
                            ->options(fn () => Semester::orderBy('name')->pluck('name','id')->all())
                            ->required(),
                        Forms\Components\TextInput::make('keterangan')->maxLength(255),
                    ]),
                Tables\Actions\DetachAction::make(),
            ]);
    }
}
