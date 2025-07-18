<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Subject;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Traits\GenerateSubjectsKode;
use Filament\Forms\Components\Fieldset;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SubjectResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SubjectResource\RelationManagers;
use App\Filament\Resources\SubjectResource\RelationManagers\JenisNilaiRelationManager;

class SubjectResource extends Resource
{
    protected static ?string $model = Subject::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Mata Pelajaran';
    
    protected static ?string $navigationGroup = 'Akademik';
    protected static ?int $navigationSort = -8;

    use GenerateSubjectsKode;

    public static function form(Form $form): Form
    {
        return $form
       
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->unique(
                        table: Subject::class,
                        column: "name",
                        ignoreRecord: true
                    )
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('name', strtoupper($state));
                    })
                    ->validationMessages([
                        "unique" => "nama sudah terpakai, ganti yg lain.",
                        "required" => "wajib diisi.",
                    ])
                    ->required(),
                Forms\Components\TextInput::make('kode')
                    ->unique(table: Subject::class, column: 'kode', ignoreRecord: true)
                    ->readOnly()
                    ->default(GenerateSubjectsKode::kode_subject())
                    ->validationMessages([
                        'unique' => 'Kode sudah maksimal, tidak bisa tambah baru lagi.',
                        'required' => 'Kode wajib diisi.',
                    ]),


                //  Forms\Components\Repeater::make('indikators')
                //     ->addActionLabel('Tambah Indikator')
                //     ->relationship('jenisNilai')
                //     ->schema([
                //         Forms\Components\TextInput::make('indikator')
                //             ->nullable(),
                //         Forms\Components\Textarea::make('keterangan')
                //             ->default('-')    
                //             ->nullable(),
                //     ]),

                Forms\Components\TextInput::make('kkm')
                    ->label('Nilai Standart KKM Nasional')
                    ->numeric(),
                Forms\Components\Select::make('jurusan')
                    ->label('Pilih Jurusan')
                    ->relationship('jurusans', 'nama')
                    ->multiple()
                    ->preload(),
                Forms\Components\Textarea::make('deskripsi')
                    ->default('-')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
         ->defaultSort('kode', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('kode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->limit(15)
                    ->searchable(),
                Tables\Columns\TextColumn::make('kkm')
                    ->label('Nilai KKM')
                    ->searchable()
                    ->getStateUsing(function ($record) {
                        return $record->kkm ?? '-';
                    }),
                Tables\Columns\TextColumn::make('jurusans_subjects.jurusan.nama')
                    ->label("Jurusan")
                    ->limit(105)
                    ->searchable()
                    ->getStateUsing(function ($record) {
                        return $record->jurusans_subjects
                            ->map(fn ($js) => $js->jurusan?->nama)
                            ->filter() // hilangkan null
                            ->implode(', ') ?: '-';
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
               //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            JenisNilaiRelationManager::class,
        ];
    }

     public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() < 10 ? 'success' : 'primary';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubjects::route('/'),
            'create' => Pages\CreateSubject::route('/create'),
            'edit' => Pages\EditSubject::route('/{record}/edit'),
        ];
    }
}
