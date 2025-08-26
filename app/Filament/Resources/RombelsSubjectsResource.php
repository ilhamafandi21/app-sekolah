<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\RombelsSubjectsResource\Pages\ListRombelsSubjects;
use App\Filament\Resources\RombelsSubjectsResource\Pages\CreateRombelsSubjects;
use App\Filament\Resources\RombelsSubjectsResource\Pages\EditRombelsSubjects;
use Filament\Forms;
use Filament\Tables;
use App\Models\Rombel;
use App\Models\Tingkat;
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

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

   public static function getEloquentQuery(): Builder
    {
        return static::getModel()::with([
            'rombel:id,kode,tingkat_id,jurusan_id,divisi',
            'subject:id,name',
        ]);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('rombel_id')
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

                Select::make('subject_id')
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
                Group::make('rombel_id')
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
                TextColumn::make('rombel.kode')
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
                TextColumn::make('subject.name')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => ListRombelsSubjects::route('/'),
            'create' => CreateRombelsSubjects::route('/create'),
            'edit' => EditRombelsSubjects::route('/{record}/edit'),
        ];
    }
}
