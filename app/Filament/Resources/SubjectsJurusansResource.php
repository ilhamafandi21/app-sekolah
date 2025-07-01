<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubjectsJurusansResource\Pages;
use App\Filament\Resources\SubjectsJurusansResource\RelationManagers;
use App\Models\Jurusan;
use App\Models\Subject;
use App\Models\SubjectsJurusans;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubjectsJurusansResource extends Resource
{
    protected static ?string $model = SubjectsJurusans::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Mata Pelajaran - Jurusan';
    
    protected static ?string $navigationGroup = 'Jurusan/Kelas/Mapel';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('jurusan_id')
                    ->options(function () {
                        return Jurusan::all()->mapWithKeys(function ($jurusan) {
                            // dd() di sini akan mencetak satu-per-satu
                            return [
                                $jurusan->id => "{$jurusan->nama} ({$jurusan->kode})",
                            ];
                        });
                    })
                    ->required(),
                Forms\Components\Select::make('subject_id')
                    ->multiple()
                    ->options(function () {
                        return Subject::all()->mapWithKeys(function ($subject) {
                            // dd() di sini akan mencetak satu-per-satu
                            return [
                                $subject->id => "{$subject->name} ({$subject->kode})",
                            ];
                        });
                    })
                    ->required(),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('subject')
                    ->label('Nama Mapel')
                    ->formatStateUsing(function ($record) {
                        return $record->subject['name'] ?? '-';
                         })
                    ->sortable(),
                Tables\Columns\TextColumn::make('jurusan.nama')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubjectsJurusans::route('/'),
            'create' => Pages\CreateSubjectsJurusans::route('/create'),
            'edit' => Pages\EditSubjectsJurusans::route('/{record}/edit'),
        ];
    }
}
