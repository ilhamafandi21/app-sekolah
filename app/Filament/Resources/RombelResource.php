<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\RombelResource\Pages\ListRombels;
use App\Filament\Resources\RombelResource\Pages\CreateRombel;
use App\Filament\Resources\RombelResource\Pages\EditRombel;
use Filament\Forms;
use Filament\Tables;
use App\Models\Rombel;
use Filament\Forms\Get;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\RombelResource\Pages;
use App\Filament\Resources\RombelResource\RelationManagers\BiayasRelationManager;
use App\Filament\Resources\RombelResource\RelationManagers\SubjectsRelationManager;
use App\Filament\Resources\RombelResource\RelationManagers\RombelsSubjectsSchedullsTeachersRelationManager;
use App\Filament\Resources\RombelResource\RelationManagers\SiswasRelationManager;
use App\Filament\Resources\RombelResource\RelationManagers\TransactionsRelationManager;

class RombelResource extends Resource
{
    protected static ?string $model = Rombel::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-arrow-right-start-on-rectangle';
    protected static ?string $navigationLabel = 'Rombel';

    public static function getEloquentQuery(): Builder
    {
       return static::getModel()::with([
            'tahun_ajaran:id,thn_ajaran',
            'tingkat:id,nama_tingkat',
            'jurusan:id,nama_jurusan,kode',
        ]);
    }

   public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('📟 Informasi Rombel')
                    ->description('Silakan lengkapi data rombongan belajar')
                    ->schema([
                        Hidden::make('kode')
                            ->dehydrated()
                            ->nullable(),

                        Grid::make(2)
                            ->schema([
                                Select::make('tahun_ajaran_id')
                                    ->label('Tahun Ajaran')
                                    ->required()
                                    ->relationship('tahun_ajaran', 'thn_ajaran')
                                    ->disabled(fn (string $context) => $context === 'edit')
                                    ->dehydrated(),

                                Select::make('tingkat_id')
                                    ->label('Tingkat')
                                    ->required()
                                    ->relationship('tingkat', 'nama_tingkat')
                                    ->disabled(fn (string $context) => $context === 'edit')
                                    ->dehydrated(),

                                Select::make('jurusan_id')
                                    ->label('Jurusan')
                                    ->required()
                                    ->relationship('jurusan', 'nama_jurusan')
                                    ->disabled(fn (string $context) => $context === 'edit')
                                    ->dehydrated(),

                                TextInput::make('divisi')
                                    ->label('Divisi')
                                    ->numeric()
                                    ->required()
                                    ->disabled(fn (string $context) => $context === 'edit')
                                    ->dehydrated(),

                            ])
                            ->columns(2),
                    ])
                    ->collapsible(),

                Section::make('📌 Status & Keterangan')
                    ->description('Informasi tambahan')
                    ->schema([
                        Toggle::make('status')
                            ->label('Aktifkan Rombel')
                            ->default(true),

                        TextInput::make('keterangan')
                            ->label('Catatan / Keterangan')
                            ->default('-')
                            ->nullable()
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('kode')
                    ->searchable(),
                TextColumn::make('info_singkat')
                    ->label('Info Kelas')
                    ->getStateUsing(function ($record) {
                        return ($record->tingkat?->nama_tingkat ?? '-') . ' ' .
                                ($record->jurusan?->kode ?? '-') . '-' .
                                ($record->divisi ?? '-');
                    })
                    ->wrap()
                    ->searchable(),
                TextColumn::make('tahun_ajaran.thn_ajaran')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tingkat.nama_tingkat')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('jurusan.nama_jurusan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('divisi')
                    ->searchable(),
                IconColumn::make('status')
                    ->boolean(),
                TextColumn::make('keterangan')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->recordActions([
                EditAction::make(),
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
            SubjectsRelationManager::class,
            RombelsSubjectsSchedullsTeachersRelationManager::class,
            SiswasRelationManager::class,
            BiayasRelationManager::class,
            TransactionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRombels::route('/'),
            'create' => CreateRombel::route('/create'),
            'edit' => EditRombel::route('/{record}/edit'),
        ];
    }
}
