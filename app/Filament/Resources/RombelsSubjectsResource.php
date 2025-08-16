<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Rombel;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\RombelsSubjects;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RombelsSubjectsResource\Pages;
use App\Filament\Resources\RombelsSubjectsResource\RelationManagers;
use App\Filament\Resources\RombelsSubjectsResource\RelationManagers\RombelsSubjectsTeachersRelationManager;
use App\Models\Tingkat;

class RombelsSubjectsResource extends Resource
{
    protected static ?string $model = RombelsSubjects::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

   public static function getEloquentQuery(): Builder
    {
        return static::getModel()::with([
            'rombel:id,kode,tingkat_id,jurusan_id,divisi',
            'subject:id,name',
            'rombel.tingkat:id,nama_tingkat',
            'rombel.jurusan:id,nama_jurusan'
        ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('rombel_id')
                    ->relationship(name: 'rombel', 
                                titleAttribute: 'kode')
                    ->getOptionLabelFromRecordUsing(function (Rombel $record) {
                         return "{$record->kode} | T{$record->tingkat->nama_tingkat} - J{$record->jurusan->nama_jurusan} - D{$record->divisi}";
                    })
            ->searchable()
            ->preload()
            ->required(),
                Forms\Components\Select::make('subject_id')
                    ->relationship('subject', 'name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function(Builder $query){
                $query
                    ->with([
                        'rombel:id,kode',
                        'subject:id,name',
                    ])
                    ->select('id', 'rombel_id', 'subject_id')
                    ->distinct();
            })
            ->columns([
                Tables\Columns\TextColumn::make('rombel.kode')
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject.name')
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
            'index' => Pages\ListRombelsSubjects::route('/'),
            'create' => Pages\CreateRombelsSubjects::route('/create'),
            'edit' => Pages\EditRombelsSubjects::route('/{record}/edit'),
        ];
    }
}
