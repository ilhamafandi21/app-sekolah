<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Rombel;
use App\Models\Tingkat;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\RombelsSubjects;
use Filament\Resources\Resource;
use Pest\Mutate\Options\IgnoreOption;
use Illuminate\Validation\Rules\Unique;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RombelsSubjectsResource\Pages;
use App\Filament\Resources\RombelsSubjectsResource\RelationManagers;
use App\Filament\Resources\RombelsSubjectsResource\RelationManagers\RombelsSubjectsTeachersRelationManager;

class RombelsSubjectsResource extends Resource
{
    protected static ?string $model = RombelsSubjects::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

   public static function getEloquentQuery(): Builder
    {
        return static::getModel()::with([
            'rombel:id,kode,tingkat_id,jurusan_id,divisi',
            'subject:id,name',
        ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('rombel_id')
                    ->relationship(name: 'rombel', 
                                titleAttribute: 'kode',
                                modifyQueryUsing: function($query){
                                    return $query
                                            ->with([
                                                'tingkat:id,nama_tingkat',
                                                'jurusan:id,nama_jurusan,kode'
                                            ])
                                            ->select('id','tingkat_id','jurusan_id', 'kode','divisi');
                                })
                    ->getOptionLabelFromRecordUsing(function (Rombel $record) {
                         return "{$record->kode} | 
                                {$record->tingkat->nama_tingkat} {$record->jurusan->kode}-{$record->divisi}";
                    })
                    ->searchable()
                    ->preload()
                    ->required(),

                // Forms\Components\Select::make('subject_id')
                //     ->relationship('subject', 'name')
                //     ->unique(ignoreRecord:true)
                //     ->required(),

                Forms\Components\Select::make('subject_id')
                    ->label('Subject')
                    ->relationship('subject', 'name')
                    ->unique(
                        modifyRuleUsing: fn (Unique $rule, Get $get) =>
                            $rule->where('rombel_id', $get('rombel_id')), // kombinasi rombel + subject
                        ignoreRecord: true, // ABAIKAN record saat edit
                    )
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function(Builder $query){
                $query
                    ->with([
                        'rombel:id,kode,tingkat_id,jurusan_id,divisi',
                        'subject:id,name',
                        'rombel.tingkat:id,nama_tingkat',
                        'rombel.jurusan:id,kode',
                    ])
                    ->select('id', 'rombel_id', 'subject_id')
                    ->distinct();
            })
            ->groups([
                Tables\Grouping\Group::make('rombel_id')
                    ->label('Rombel')
                    ->getTitleFromRecordUsing(fn ($record) => 
                                $record->rombel->kode
                                . ' - ' . ($record->rombel?->tingkat?->nama_tingkat ?? '-')
                                . ' ' . ($record->rombel?->jurusan?->kode ?? '-')
                                . '-' . ($record->rombel?->divisi ?? '-')?? '-')
                    ->collapsible(),
            ])
            ->defaultGroup('rombel_id')
            ->columns([
                Tables\Columns\TextColumn::make('rombel.kode')
                    ->html() // <<< penting, agar HTML di bawah dirender
                    ->formatStateUsing(function ($state, $record) {
                        $text = ($state ?? '-')
                            . ' - ' . ($record->rombel?->tingkat?->nama_tingkat ?? '-')
                            . ' ' . ($record->rombel?->jurusan?->kode ?? '-')
                            . '-' . ($record->rombel?->divisi ?? '-');

                        // geser ke kanan 2.5rem
                        return '<div style="padding-left: 11.5rem;">' . e($text) . '</div>';
                    })
                    // supaya sorting tidak ikut <div> HTML
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
                Tables\Actions\DeleteAction::make(),
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
